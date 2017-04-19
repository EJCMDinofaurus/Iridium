<?php

	class ExemploDataAccess extends DataAccess
	{
		public function makeObject($data)
		{
			$obj = new Exemplo();

			$obj->setId((isset($data["id"])) ? $data["id"] : null);
			$obj->setValor((isset($data["valor"])) ? $data["valor"] : null);

            return $obj;
		}

		public function makeParameters($obj)
		{
			$parametros = array();

			if($obj != null) {
				if($obj->getId() !== null) $parametros["id"] = $obj->getId();
				if($obj->getValor() !== null) $parametros["valor"] = $obj->getValor();
			}

			return $parametros;
		}

		public function selecionarTodosExemplos()
		{
			return $this->select("SELECT * FROM exemplo");
		}

		public function selecionarExemploPorId($id)
		{
			$filtro = new Exemplo();
			$filtro->setId($id);
			return $this->selectOne("SELECT * FROM exemplo WHERE id = :id", $filtro);
		}

		public function quantidadeExemplo()
		{
			return $this->count("SELECT * FROM exemplo");
		}

		public function inserirExemplo(Exemplo $exemplo)
		{
			return $this->insert("INSERT INTO exemplo (valor) VALUES (:valor)", $exemplo);
		}

		public function alterarExemplo(Exemplo $exemplo)
		{
			return $this->update("UPDATE exemplo SET valor = :valor WHERE id = :id", $exemplo);
		}

		public function excluirExemploPorId($id)
		{
			$filtro = new Exemplo();
			$filtro->setId($id);
			return $this->delete("DELETE FROM exemplo WHERE id = :id", $filtro);
		}

	}
