<?php
	abstract class Controller {

		protected $controllerName;
		protected $actionName;
		protected $permissionHandler = null;

        /**
         * @var null|Neon
         */
        protected $view = null;
        /**
         * @var null|Controller
         */
        protected static $controller = null;

		/**
		 * Construtor da classe base Controller
		 */
		private function __construct()
		{

		}

		/**
		 * Cria um novo Controller do tipo $controllerName.
		 * Caso não seja passado um tipo, é criado o Controller padrão.
		 * @static
		 * @param string|null $controllerName
		 * @return Controller
		 */
		public static function load($controllerName = null)
		{
			if($controllerName == null) { //Caso seja chamado o Controller Default
				$controllerName = Application::getDefaultControllerName();
			}

			if(Controller::exists($controllerName)) { //Caso o Controller exista, retorna uma instância dele
				$fullControllerName = $controllerName . CONTROLLER_SUFFIX;
                self::$controller = new $fullControllerName();
				self::$controller->setControllerName($controllerName);
			} else {
				Application::send404();
			}
		}

		/**
		 * Verifica se um nome passado é um Controller existente.
		 * @static
		 * @param string $name
		 * @return bool
		 */
		public static function exists($name) {
			return class_exists($name . CONTROLLER_SUFFIX);
		}

		/**
		 * Veririca se um nome passado é uma action existente do Controller.
		 * @param string $name
		 * @return bool
		 */
		public function actionExists($name) {
			return method_exists($this, $name);
		}

		/**
		 * Executa uma Action com o nome $name, passando a ela os parâmetros $parameters.
		 * Caso não seja passada um nome, a Action padrão é executada.
		 * @param string|null $name
		 * @param array|null $parameters
		 */
		public function loadAction($name = null, $parameters = array())
		{
			//Caso o nome ou os parâmetros sejam nulos, atribui o valor padrão
			if($name == null) {
				$name = Application::getDefaultActionName();
			}

			if($this->actionExists($name)) {//Caso a Action exista, chama a Action
				//Preenchendo o nome da Action
				$this->actionName = $name;

				//Criando o objeto Neon no Controller
				$controllerNameFolder = strtolower($this->controllerName[0]) . substr($this->controllerName, 1);
				$this->view = new Neon($controllerNameFolder . "/" . $this->actionName);


				//Sistemas de Permissão entra aqui
				if($this->permissionHandler != null)
				{
					if($this->permissionHandler->checkPermission($this->actionName))
						//Chamando a Action, passando os parâmetros
						call_user_func_array(array($this, $this->actionName), $parameters);
					else
						//YOU SHALL NOT PASS
						$this->redirect($this->permissionHandler->getRedirectPage($this->actionName));
				}
				else
				{
					call_user_func_array(array($this, $this->actionName), $parameters);
				}
			}
			else
			{
				//Caso a Action não exista, redireciona para a página de erros padrão
				$this->send404();
			}
		}

		/**
		 * Define o nome do Controller.
		 * @param string $name
		 */
		public function setControllerName($name)
		{
			$this->controllerName = $name;
		}

        /**
         * @return null|Controller
         */
        public static function getInstance()
        {
            return self::$controller;
        }

		public function send404()
		{
			Application::send404();
		}

        public function redirect($toUrl, $absolute = true)
        {
			Application::redirect($toUrl, $absolute);
        }

        public function renderJSON($data)
        {
            Application::setResponseContentType("application/json");
            echo json_encode($data);
            exit();
        }

	}