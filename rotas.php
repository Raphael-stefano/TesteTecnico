<?php 

    use Core\Helper;

    use Pecee\SimpleRouter\SimpleRouter;
    use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

    try{
        SimpleRouter::setDefaultNamespace("Sistema\Controlador");
        
        SimpleRouter::group(['prefix' => 'TesteTecnico'], function () {
            SimpleRouter::get('/home', 'SiteControlador@home');
            SimpleRouter::get('/login', 'SiteControlador@login');
            SimpleRouter::post('/autenticar', 'SiteControlador@autenticar');
            SimpleRouter::get('/404', 'SiteControlador@erro404');
        });

        SimpleRouter::group(['prefix' => 'TesteTecnico/admin'], function () {
            SimpleRouter::match(['get', 'post'], '/dashboard', 'AdminControlador@dashboard');
            SimpleRouter::get('/sair', 'AdminControlador@sair');
            SimpleRouter::get('/novo', 'AdminControlador@novo');
            SimpleRouter::post('/salvar_novo', 'AdminControlador@salvar');
            SimpleRouter::get('/editar/{id}', 'AdminControlador@editar');
            SimpleRouter::post('/salvar_alteracoes/{id}', 'AdminControlador@salvarAlteracoes');
            SimpleRouter::get('/confirmar_deletar/{id}', 'AdminControlador@confirmarDeletar');
            SimpleRouter::get('/deletar/{id}', 'AdminControlador@deletar');
        });

        SimpleRouter::start();

    } catch(NotFoundHttpException $ex){
        Helper::redirecionar("404");
    }

?>