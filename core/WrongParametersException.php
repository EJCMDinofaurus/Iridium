<?php

class WrongParametersException extends Exception {

    public function __construct($query, $parameterNames)
    {
        $parameters = "";
        if($parameterNames)
            $parameters = implode(", ", $parameterNames);
        else
            $parameters = "(nenhum)";

        $message = join("\n", array(
            "Os parâmetros enviados para a query não são os esperados.",
            "(Deve haver exatamente um parametro na query para cada um enviado e vice-versa)",
            "A query: ",
            $query,
            "",
            "Os parâmetros:",
            $parameters
        ));

        parent::__construct($message, 0);
    }

}