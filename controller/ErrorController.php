<?php
	class ErrorController extends Controller
	{

		public function e404()
		{
			$this->view->render();
		}

		public function e500()
		{
			$this->view->render();
		}

		public function e403()
		{
			$this->view->render();
		}
	}
