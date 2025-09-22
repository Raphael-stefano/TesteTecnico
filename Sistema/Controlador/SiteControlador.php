<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Core\Helper;
    use Core\Sessao;
    use Exception;
    use Sistema\Modelo\Usuario;

    class SiteControlador extends Controlador{

        public function __construct(){
            parent::__construct("Templates/Site");
        }

        /**
         * esta págona nao foi desenvolvida para essa aplicaçao, porém foi criada a rota meramente para fins de demonstraçao de redirecionamento
         *
         * @return void
         */
        public function home(): void{
            echo $this->template->renderizar("home.html.twig", []);
        }

        /**
         * Renderiza o foumulário de login
         *
         * @return void
         */
        public function login(): void{
            echo $this->template->renderizar("login.html.twig", []);
        }

        /**
         * Autentica o usuário. Caso haja falha na autenticaçao, redireciona para a página de login. Caso haja sucesso, mas o nível do usuário seja 1, redireciona para a home. Casp haja sucesso e o nível do usuário seja 2, redireciona para o dashboard.
         *
         * @return void
         */
        public function autenticar(): void{
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(static::validarLogin($dados['email'], $dados['senha'])){
                $usuario = Usuario::lerEspecifico($dados['email']);

                $usuario->autenticar();

                if($usuario->nivel === 1){
                    Helper::redirecionar('/home');
                }
                if($usuario->nivel === 2){
                    Helper::redirecionar('/admin/dashboard');
                }
            } else{
                $this->mensagem->alerta("Credenciadas inválidas. Verifique E-mail e senha.")->flash();
                Helper::redirecionar('/login');
            }

            //Helper::redirecionar('/home');
        }

        /**
         * Redireciona para uma página de erro 404 caso nao seja encontrada a página
         *
         * @return void
         */
        public function erro404(): void{
            echo $this->template->renderizar("404.html.twig", []);
        }

        /**
         * Encerra a sessao
         *
         * @return void
         */
        public function sair(): void{
            Sessao::deletar();
            $this->mensagem->alerta("Voce saiu da sua conta!")->flash();
            Helper::redirecionar('/login');
        }

        /**
         * Valida as credenciais do usuario
         *
         * @param string $email
         * @param string $senha
         * @return boolean
         */
        private static function validarLogin(string $email, string $senha): bool{
            try {

                $email = trim($email);
                $senha = trim($senha);
                
                if (empty($email) || empty($senha)) {
                    return false;
                }
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

                $usuario = Usuario::lerEspecifico($email);
                
                if (!$usuario instanceof Usuario) {
                    return false;
                }

                return password_verify($senha, $usuario->senha);

            } catch (Exception $e) {
                error_log("Erro ao validar login: " . $e->getMessage());
                return false;
            }
        }

    }

?>