<?php
	abstract class DataAccess
	{
        /**
         * @var Connection[]
         */
        private static $connections = array();
		private static $defaultConnectionName;
        private static $PARAMETER_ERROR_SQLSTATE = "HY093";

		protected static $PDOConnections = array();

        protected $connection;

		abstract public function makeObject($data);
		abstract public function makeParameters($obj);

		public function __construct($connectionName = null)
		{
			if($connectionName == null)
			{
				$connectionName = self::$defaultConnectionName;
			}

			if(!isset(self::$PDOConnections[$connectionName]))
			{
				$conn = self::$connections[$connectionName];

				$this->connection = new PDO($conn->getDSN(), $conn->getUser(), $conn->getPassword(), array(
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".$conn->getEncoding()
				));
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				self::$PDOConnections[$connectionName] = $this->connection;
			} else
			{
				$this->connection = self::$PDOConnections[$connectionName];
			}
		}

		public static function addConnection($name, Connection $connection)
		{
			if(count(self::$connections) == 0) {
				self::$defaultConnectionName = $name;
			}

			self::$connections[$name] = $connection;
		}

		public static function setDefaultConnectionName($name)
		{
			self::$defaultConnectionName = $name;
		}

		private function executeQuery($query, $filter)
		{
			// aceita arrays ou um objeto
			if(is_array($filter))
			{
				$parameters = $filter;
			}
			else
			{
				$parameters = $this->makeParameters($filter);
			}

			// resolve bug no pdo em que variaveis tipo int são pasadas para a query como strings
			$paramType = null;
			foreach($parameters as $paramKey => $paramValue)
			{
				if(is_int($paramValue))
					$paramType = PDO::PARAM_INT;
				elseif(is_bool($paramValue))
					$paramType = PDO::PARAM_BOOL;
				elseif(is_null($paramValue))
					$paramType = PDO::PARAM_NULL;
				else
					$paramType = PDO::PARAM_STR;

				$query->bindValue($paramKey, $paramValue, $paramType);
			}

            try {
                return $query->execute();
            }
            catch(PDOException $ex)
            {
                if($ex->errorInfo[0] == self::$PARAMETER_ERROR_SQLSTATE)
                {
                    throw new WrongParametersException($query->queryString, array_keys($parameters));
                }
                else
                {
                    throw $ex;
                }
            }
		}

		protected function select($preparedQuery, $filter = array())
		{
			$qry = $this->connection->prepare($preparedQuery);

			$this->executeQuery($qry, $filter);

			$result = array();
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				$result[] = $this->makeObject($res);
			}
			return $result;
		}

		protected function selectOne($preparedQuery, $filter = array())
		{
			$resultado = $this->select($preparedQuery, $filter);
			switch(count($resultado))
			{
				case 0:
					throw new DataAccessException("A query '$preparedQuery' não retornou nenhum resultado.");
				case 1:
					return $resultado[0];
				default:
					throw new DataAccessException("A query '$preparedQuery' retornou dois ou mais resultados.");
			}
		}

		protected function count($preparedQuery, $filter = array())
		{
			$qry = $this->connection->prepare($preparedQuery);

			$this->executeQuery($qry, $filter);

			$quantidade = $qry->fetch(PDO::FETCH_NUM);

			return (int)$quantidade[0];
		}

		protected function insert($preparedQuery, $filter = array())
		{
			$qry = $this->connection->prepare($preparedQuery);

			$this->executeQuery($qry, $filter);

			return $this->connection->lastInsertId();
		}

		protected function update($preparedQuery, $filter = array())
		{
			$qry = $this->connection->prepare($preparedQuery);

			$this->executeQuery($qry, $filter);
		}

		protected function delete($preparedQuery, $filter = array())
		{
			$qry = $this->connection->prepare($preparedQuery);

			$this->executeQuery($qry, $filter);
		}
	}