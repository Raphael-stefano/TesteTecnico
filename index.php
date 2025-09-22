<?php
    require_once "vendor/autoload.php";
    require_once "Sistema/configuracao.php";
    
    use Core\Sessao;
    use Core\Helper;

    $sessao = new Sessao();

    require "rotas.php";

?>