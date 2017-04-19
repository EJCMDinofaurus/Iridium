<?php
// framework 1.3
// Configurações do projeto

Application::setEnvironment(DEVELOPMENT_ENVIRONMENT);

switch(Application::getEnvironment())
{
	case DEVELOPMENT_ENVIRONMENT:

        Application::discoverWebRoot();

		//Criando conexões de Banco de Dados
		DataAccess::addConnection("main", new Connection("mysql", "127.0.0.1", "root", "", "test"));

		Application::setSessionName("IRIDIUM");

		break;
	case PRODUCTION_ENVIRONMENT:

		//todo mudar URL de produção, nome da sessão e conexão ao banco

		Application::setWebRoot('http://localhost/ejcm/ejcm-iridium-framework/trunk/06.Implementacao/public_html/');

		break;
}

//Definindo a conexão principal do Banco de Dados
DataAccess::setDefaultConnectionName("main");

//Configuração de Comportamento Padrão do Projeto
Application::setViewDefaulPlataform("web");
Application::setDefaultControllerName("home");
Application::setDefaultActionName("index");
Application::setError404Path("Error/e404");
Application::setError500Path("Error/e500");
Application::setError403Path("Error/e403");
Application::setViewDefaultTemplate("default");
Application::setViewDefaultTemplateFile("main.php");
Application::setDefaultDatabaseEnconding("utf8");

//Include de Bibliotecas


//Configuração de Rotas
// -----Em construção-----