<?php
/**
 * Neon , a Simple Template Engine built for fun and profit.
 * @author: Felipe de Souza
 */

class Neon
{
    // *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
    // Essa classe é protegida pelo super dragão dos comentários cinzas
    // não tente modifica-la sem sua permissão!
    // *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
    /*
                                                      .~))>>
                                                     .~)>>
                                                   .~))))>>>
                                                 .~))>>             ___
                                               .~))>>)))>>      .-~))>>
                                             .~)))))>>       .-~))>>)>
                                           .~)))>>))))>>  .-~)>>)>
                       )                 .~))>>))))>>  .-~)))))>>)>
                    ( )@@*)             //)>))))))  .-~))))>>)>
                  ).@(@@               //))>>))) .-~))>>)))))>>)>
                (( @.@).              //))))) .-~)>>)))))>>)>
              ))  )@@*.@@ )          //)>))) //))))))>>))))>>)>
           ((  ((@@@.@@             |/))))) //)))))>>)))>>)>
          )) @@*. )@@ )   (\_(\-\b  |))>)) //)))>>)))))))>>)>
        (( @@@(.@(@ .    _/`-`  ~|b |>))) //)>>)))))))>>)>
         )* @@@ )@*     (@)  (@) /\b|))) //))))))>>))))>>
       (( @. )@( @ .   _/  /    /  \b)) //))>>)))))>>>_._
        )@@ (@@*)@@.  (6///6)- / ^  \b)//))))))>>)))>>   ~~-.
     ( @jgs@@. @@@.*@_ VvvvvV//  ^  \b/)>>))))>>      _.     `bb
      ((@@ @@@*.(@@ . - | o |' \ (  ^   \b)))>>        .'       b`,
       ((@@).*@@ )@ )   \^^^/  ((   ^  ~)_        \  /           b `,
         (@@. (@@ ).     `-'   (((   ^    `\ \ \ \ \|             b  `.
           (*.@*              / ((((        \| | |  \       .       b `.
                             / / (((((  \    \ /  _.-~\     Y,      b  ;
                            / / / (((((( \    \.-~   _.`" _.-~`,    b  ;
                           /   /   `(((((()    )    (((((~      `,  b  ;
                         _/  _/      `"""/   /'                  ; b   ;
                     _.-~_.-~           /  /'                _.'~bb _.'
                   ((((~~              / /'              _.'~bb.--~
                                      ((((          __.-~bb.-~
                                                  .'  b .~~
                                                  :bb ,'
                                                  ~~~~
    */

    private $currentBlockName, $darthVader, $loadingTemplate, $blocks, $data;
    private $TEMPLATE_PATH, $VIEW_PATH;

    public function __construct($pathToView)
    {
        $this->blocks = array();
        $this->data  = array();
        $this->loadingTemplate = false;

        $this->TEMPLATE_PATH = LOCAL_ROOT . VIEW_FOLDER . 'web/templates/';
        $this->VIEW_PATH = LOCAL_ROOT . VIEW_FOLDER . "web/pages/";

        $this->pathToView = $this->VIEW_PATH . $pathToView . ".php";
    }

    public function set($key, $value = null)
    {
        if(is_array($key))
        {
            $this->data = array_merge($this->data, $key);
        }
        elseif(is_string($key) && func_num_args() == 2)
        {
            $this->data[$key] = $value;
        }
        else
        {
            throw new NeonException("O método 'set' só aceita uma array de chaves/valor ou".
                " um par chave/valor de parametros");
        }

        return $this;
    }

    public function url($route)
    {
        echo Application::getWebRoot(),$route;
    }

    public function e($content)
    {
        echo htmlspecialchars($content, ENT_QUOTES, "UTF-8");
    }

    public function setTemplate($template)
    {
        $this->darthVader = $template;
        return $this;
    }

    public function render()
    {
        echo $this->load($this->pathToView);
    }

    private function load($pathToView)
    {
        if(file_exists($pathToView))
        {
            ob_start();
            extract($this->data);
            try {
                include $pathToView;

                if($this->insideBlock())
                {
                    throw new NeonException("A view terminou mas o bloco '$this->currentBlockName' ainda está aberto.");
                }
            }
            catch(Exception $ex)
            {
                if($this->insideBlock())
                {
                    ob_end_clean();
                }
                ob_end_clean();
                throw $ex;
            }
        }
        else
        {
            throw new NeonException(
                "Não foi possível renderizar a view porque o arquivo\n'".
                "$pathToView' não existe.\n".
                "Verifique se o arquivo foi realmente criado.");
        }
        if($this->defaultBlockNotOverrided())
        {
            $this->blocks[NEON_DEFAULT_BLOCK_NAME] = ob_get_contents();
        }
        ob_clean();

        return $this->loadDaddy($this->darthVader);
    }

    private function loadDaddy($template)
    {
        $template = $template ? $template : Application::getViewDefaultTemplate();
        $path = $this->TEMPLATE_PATH . $template . '/' . Application::getViewDefaultTemplateFile();

        if(file_exists($path))
        {
            $this->loadingTemplate = true;
            ob_start();
            extract($this->data);

            try {
                include $path;
            }
            catch(Exception $ex)
            {
                $this->loadingTemplate = false;
                ob_end_clean();
                throw $ex;
            }
        }
        else
        {
            $this->loadingTemplate = false;
            throw new NeonException(
                "Não foi possível achar a template porque o arquivo\n'".
                "$path' não existe.\n".
                "Verifique se o arquivo foi realmente criado.");
        }

        $this->loadingTemplate = false;
        return ob_get_clean();
    }

    private function defaultBlockNotOverrided()
    {
        return !isset($this->blocks[NEON_DEFAULT_BLOCK_NAME]);
    }

    private function import($filePath)
    {
        $path = LOCAL_ROOT . VIEW_FOLDER . 'Web/Templates/' . $filePath;
        if(file_exists($path))
        {
            extract($this->data);
            include($path);
        }
        else
            throw new NeonException("Não foi possível importar o arquivo '$path'\n".
                                    "porque ele não foi encontrado.");
    }

    private function start($name)
    {
        if($this->loadingTemplate)
        {
            throw new NeonException("Não é possível chamar o método 'start' em um template.");
        }

        if($this->insideBlock())
        {
            throw new NeonException("O método 'start' não pode ser chamado enquanto ".
                                    "o bloco '$this->currentBlockName' não for fechado.");
        }

        $this->currentBlockName = $name;
        ob_start();
    }

    private function block($name)
    {
        echo isset($this->blocks[$name]) ? $this->blocks[$name] : "";
    }

    private function end()
    {
        if($this->loadingTemplate)
        {
            throw new NeonException("Não é possível chamar o método 'end' em um template.");
        }

        if($this->insideBlock())
        {
            $this->blocks[$this->currentBlockName] = ob_get_contents();
            $this->currentBlockName = null;

            ob_end_clean();
        }
        else
        {
            throw new NeonException("O método 'end' foi chamado mas não existe nenhum bloco aberto.");
        }
    }

    private function insideBlock()
    {
        return $this->currentBlockName != null;
    }
}