<?php

    namespace Core;
    require_once "Sistema/configuracao.php";
    require 'vendor/autoload.php';
    use Exception;

    class Helper{
        
        /**
         * Verifica se a senha possui entre 4 e 20 caracteres, limites mínimo e máximo de número de caracteres 
         *
         * @param string $senha 
         * @return boolean
         */
        public static function validarSenha(string $senha): bool{
            if(mb_strlen($senha) >= 4 AND mb_strlen($senha) <= 20){
                return true;
            }
            return false;
        }

        /**
         * salva a senha criptografada
         *
         * @param string $senha
         * @return string
         */
        public static function gerarSenha(string $senha): string{
            return password_hash($senha, PASSWORD_DEFAULT);
        }

        /**
         * compara uma senha com a senha descriptografada no banco de dados
         *
         * @param string $senha
         * @param string $hash
         * @return boolean
         */
        public static function verificarSenha(string $senha, string $hash): bool{
            return password_verify($senha, $hash);
        }

        /**
         * Mostra uma mensagem flash, se houver, na tela do usuário
         *
         * @return string|null
         */
        public static function flash(): ?string{
            $sessao = new Sessao();
            if($flash = $sessao->flash()){
                echo $flash;
            }
            return null;
        }

        /**
         * redireciona para uma url dentro do site
         *
         * @param string|null $url
         * @return void
         */
        public static function redirecionar(string $url = null): void{
            header("HTTP/1.1 302 Found");
            $local = $url ? self::url($url) : self::url();
            header("Location: {$local}");
            exit();
        }

        /**
         * Formata um número
         * @param float $valor Nímero a ser formatado
         * @param int $casas_decimais numero de casas decimais
         * @return string retorna o número arredondado para baixo em duas casas decimais, ou em outro número especidicado de casas decimais, com a parte decimal sendo separada por vírgula e os milhares sendo separados por ponto
         */
        public static function formatarValor(float $valor = null, int $casas_decimais = 2): string{
            return number_format(($valor ?? 0), $casas_decimais, ',', '.');
        }

        /**
         * Formata um número para formato monetário em reais
         *
         * @param float|null $valor número a ser formatado
         * @return string número formatado em duas casas decimais antecedido de 'R$'
         */
        public static function formatarMonetario(float $valor = null): string{
            return "R$ ".self::formatarValor($valor);
        }

        /**
         * filtro de email
         *
         * @param string $email email a ser validado
         * @return boolean se é válido ou nao
         */
        public static function validarEmail(string $email): bool{
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        /**
         * Verifica se a conexao é ou nao local
         *
         * @return boolean
         */
        public static function localhost(): bool{
            $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

            if($servidor == 'localhost') return true;

            return false;
        }

        /**
         * Retorna uma url a partir do ambiente em que a aplicaçao está rodando
         *
         * @param string $url
         * @return string
         */
        public static function url(string $url = ""): string{
            $ambiente = self::localhost() ? LINK_LOCAL : LINK_EXTERNO;
            if(str_starts_with($url, '/')) return $ambiente.$url;
            return $ambiente.'/'.$url;
        }

        /**
         * Gera uma url amigavel
         *
         * @param string $url url a ser tratada
         * @return string
         */
        public static function slug(string $url): string{

            $mapa = [
                'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
                'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
                'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
                'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
                'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
                'ç' => 'c', 'ñ' => 'n',
                'Á' => 'a', 'À' => 'a', 'Ã' => 'a', 'Â' => 'a', 'Ä' => 'a',
                'É' => 'e', 'È' => 'e', 'Ê' => 'e', 'Ë' => 'e',
                'Í' => 'i', 'Ì' => 'i', 'Î' => 'i', 'Ï' => 'i',
                'Ó' => 'o', 'Ò' => 'o', 'Õ' => 'o', 'Ô' => 'o', 'Ö' => 'o',
                'Ú' => 'u', 'Ù' => 'u', 'Û' => 'u', 'Ü' => 'u',
                'Ç' => 'c', 'Ñ' => 'n',
                '&' => 'e', '@' => '', '%' => '', '$' => '', '#' => '',
                '*' => '', '+' => '', '=' => '', ',' => '', '.' => '',
                '!' => '', '?' => '', ':' => '', '(' => '', ')' => '',
                '[' => '', ']' => '', '{' => '', '}' => ''
            ];

            $slug = strtr($url, $mapa);

            $slug = strip_tags(trim($slug));

            //$slug = trim('-', $slug);

            $slug = mb_strtolower($slug, 'UTF-8');

            $slug = preg_replace('/[\s-]+/', '-', $slug);

            return $slug;
        }
    }

?>