<?php
	class HomeController extends Controller
	{
        public function __construct()
        {
            $permissionHandler = new GandAuth_PermissionHandler("nivel", 0);
            //adiciona os niveis de usuários permitidos pela action
            $permissionHandler->addAction("onlyAdmin", array(1));
            $this->permissionHandler = $permissionHandler;
        }

		public function index()
		{
			$this->view->render();
		}

        public function onlyAdmin()
        {
            //... só o usuário com nivel "1" vê
        }
	}