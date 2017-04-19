<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Title</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <link rel="stylesheet" href="<?php $this->url("static/styles/main.css") ?>" type="text/css">
        <script type="text/javascript" src="<?php $this->url("static/scripts/libraries/html5shiv.js") ?>"></script>
    </head>

    <body>
        <header id="headerPrincipal">
            Header.
        </header>

        <nav id="navPrincipal">
            Menu: <a href="<?php $this->url("home") ?>">Home</a> | <a href="<?php $this->url("exemplo") ?>">Exemplo</a>
        </nav>

        <div class="container">
            <?php $this->block('content'); ?>
        </div>

        <footer id="footerPrincipal">
            Footer.
        </footer>

        <script type="text/javascript" src="<?php $this->url("static/scripts/libraries/jquery-1.8.1.min.js") ?>"></script>
        <script type="text/javascript" src="<?php $this->url("static/scripts/main.js") ?>"></script>
    </body>
</html>
