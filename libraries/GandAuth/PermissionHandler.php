<?php
/*                         ,---.
                          /    |
                         /     |
     GandAuth           /      |
                       /       |
                  ___,'        |
                <  -'          :
                 `-.__..--'``-,_\_
                    |o/ <o>` :,.)_`>
                    :/ `     ||/)
                    (_.).__,-` |\
                    /( `.``   `| :
                    \'`-.)  `  ; ;
                    | `       /-<
                    |     `  /   `.
    ,-_-..____     /|  `    :__..-'\
   /,'-.__\\  ``-./ :`      ;       \
`\ `\  `\\  \ :  (   `  /  ,   `. \
     \` \   \\   |  | `   :  :     .\ \
      \ `\_  ))  :  ;     |  |      ): :
     (`-.-'\ ||  |\ \   ` ;  ;       | |
      \-_   `;;._   ( `  /  /_       | |
       `-.-.// ,'`-._\__/_,'         ; |
          \:: :     /     `     ,   /  |
           || |    (        ,' /   /   |
           ||                ,'   / SSt|
		   
                 You shall not pass

*/
class GandAuth_PermissionHandler implements IPermission
{
    private $actions = array(); // Array de objetos da classe Action
    private $userLevelSession; // Nome da Sessions que está guardado o nível do usuário

    function __construct($userLevelSessionIndex, $levelDefaultUser)
    {
        if(!isset($_SESSION[$userLevelSessionIndex]))
            $_SESSION[$userLevelSessionIndex] = $levelDefaultUser;

        $this->userLevelSession = $userLevelSessionIndex;
    }

    public function addAction($name, $levels, $redirectPage = "Error/e403")
    {
        $this->actions[$name] = new GandAuth_Action($levels, $redirectPage);
    }

    public function checkPermission($actionName)
    {
        // Pega a action pelo nome e manda pra $actionChecked
        $actionChecked = $this->getActionByName($actionName);

        //Lógicoa pra checar se o nível o usuário é igual ao da action
        if($actionChecked != null)
        {
            if(!$actionChecked->checkLevel($_SESSION[$this->userLevelSession]))
                return false;
        }

        return true;
    }

    public function getRedirectPage($actionName)
    {
        $actionChecked = $this->getActionByName($actionName);

        if($actionChecked != null){
            return $actionChecked->getRedirectPage();
        }
    }

    private function getActionByName($actionName)
    {
        return isset($this->actions[$actionName]) ? $this->actions[$actionName] : null;
    }

    public function setUserLevelSession($userLevelSession)
    {
        $this->userLevelSession = $userLevelSession;
    }

}
