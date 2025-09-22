<?php

    namespace Core;

    class Sessao{

        public function __construct(){
            if(!session_id()){
                session_start();
            }
        }

        /**
         * Carrega o objeto da sessao
         *
         * @return object|null
         */
        public static function carregar(): ?object{
            if (!session_id()) {
                session_start();
            }
            return (object) $_SESSION;
        }

        /**
         * Retorna se há um usuário autenticado ou nao
         *
         * @return boolean
         */
        public static function autenticado(): bool{
            if (!session_id()) {
                session_start();
            }
            return isset($_SESSION['id_usuario']);
        }

        /**
         * Retorna se uma determinada chave está setada
         *
         * @param string $chave
         * @return boolean
         */
        public static function checar(string $chave): bool{
            if (!session_id()) {
                session_start();
            }
            return isset($_SESSION[$chave]);
        }

        /**
         * cria uma chave e atribui um valor a ela
         *
         * @param string $chave
         * @param mixed $valor
         * @return void
         */
        public static function criar(string $chave, mixed $valor): void{
            $_SESSION[$chave] = is_array($valor) ? (object) $valor : $valor;
        }

        /**
         * Limpa uma determinada chave da sessao
         *
         * @param string $chave
         * @return void
         */
        public static function limpar(string $chave): void{
            unset($_SESSION[$chave]);
        }

        /**
         * Destroi a sessao
         *
         * @return void
         */
        public static function deletar(): void{
            session_destroy();
        }

        /**
         * permite acessar uma chave da sessao
         *
         * @param [type] $atributo
         * @return void
         */
        public function __get($atributo){
            if(!empty($_SESSION[$atributo])){
                return $_SESSION[$atributo];
            } else{
                return null;
            }
        }

        public function flash(): ?Mensagem{
            if($this->checar('flash')){
                $flash = $this->flash;
                $this->limpar('flash');
                return $flash;
            }
            return null;
        }
    }

?>