<?php
	class Connection
	{
		private $driver, $host, $user, $password, $database, $encoding;

		public function __construct($driver, $host, $user, $password, $database, $encoding = null)
		{
			$this->driver = $driver;
			$this->host = $host;
			$this->user = $user;
			$this->password = $password;
			$this->database = $database;
			$this->encoding = $encoding;
		}

		public function getDSN()
		{
			return "{$this->driver}:host={$this->host};dbname={$this->database}";
		}

		public function getDatabase()
		{
			return $this->database;
		}

		public function getDriver()
		{
			return $this->driver;
		}

		public function getEncoding()
		{
			return $this->encoding ? $this->encoding : Application::getDefaultDatabaseEnconding();
		}

		public function getHost()
		{
			return $this->host;
		}

		public function getPassword()
		{
			return $this->password;
		}

		public function getUser()
		{
			return $this->user;
		}


	}