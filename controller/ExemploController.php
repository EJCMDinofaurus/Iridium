<?php

	class ExemploController extends Controller
	{
		public function index()
		{
            $this->view->render();
        }

		public function listar() {
			//$exemploDAO = new ExemploDataAccess();
			/* Implementar lógica de listar
			 * Exemplo de implementação simples:
			 * $exemplos = $exemploDAO->selecionarTodosExemplos();
			 * $this->view->set("exemplos", $exemplos);
			 */
			$this->view->render();
		}

		public function visualizar()
		{
			//$ex = new ExemploDataAccess();
			//$exemploDAO = new ExemploDataAccess();
			/* Implementar lógica de visualizar
			 * Exemplo de implementação simples:
			 * $exemplo = $exemploDAO->selecionarExemploPorId($idExemplo);
			 * $this->view->set("exemplo", $exemplo);
			 */
			$this->view->render();
		}

		public function inserir()
		{
			//$exemploDAO = new ExemploDataAccess();
			/* Implementar lógica de inserir
			 * Exemplo de implementação simples:
			 * $exemplo = new Exemplo();
			 * $exemplo->setValor($_POST["valor"]);
			 * $exemplo->setId($exemploDAO->inserirExemplo($exemplo));
			 * $this->view->set("sucesso", ($exemplo->getId() != null) ? true : false);
			 */
			$this->view->render();
		}

		public function alterar()
		{
			//$exemploDAO = new ExemploDataAccess();
			/* Implementar lógica de alterar
			 * Exemplo de implementação simples:
			 * $exemplo = new Exemplo();
			 * $exemplo->setId($_POST["id"]);
			 * $exemplo->setValor($_POST["valor"]);
			 * $resultado = $exemploDAO->alterarExemplo($exemplo));
			 * $this->view->set("sucesso", $resultado);
			 */
			$this->view->render();
		}

		public function excluir()
		{
			//$exemploDAO = new ExemploDataAccess();
			/* Implementar lógica de excluir
			 * Exemplo de implementação simples:
			 * $resultado = $exemploDAO->excluirExemplo($idExemplo));
			 * $this->view->set("sucesso", $resultado);
			 */
			$this->view->render();
		}

	}
