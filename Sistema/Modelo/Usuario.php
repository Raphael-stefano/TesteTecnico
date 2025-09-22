<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
    use PDO;
    use PDOException;
    use Exception;
    use InvalidArgumentException;
    use Core\Sessao;

    class Usuario{
        protected int $id;
        protected string $nome; 
        protected string $email;
        protected string $senha;
        protected int $nivel;
        
        public function __construct(int $id = 0, string $nome = '', string $email = '', string $senha = '', int $nivel = 1){
            $this->id = $id;
            $this->nome = $nome;
            $this->email = $email;
            $this->senha = $senha;
            $this->nivel = $nivel;
        }

        /**
         * Previne que a senha seja exibida em var_dump() e debug
         * 
         * @return array
         */
        public function __debugInfo(): array{
            return [
                'id' => $this->id,
                'nome' => $this->nome,
                'email' => $this->email,
                'nivel' => $this->nivel,
                'senha' => '****'
            ];
        }

        /**
         * Aplica um Select sem o uso de nenhuma condicional
         *
         * @return array
         */
        public static function ler(): array{
            try {
                $query = "SELECT * FROM usuarios";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Erro ao ler usuários: " . $e->getMessage());
                return [];
            }
        }

        /**
         * Aplica um Select utilizando de uma condicional
         *
         * @param string $atributo
         * @param [type] $condicao
         * @return array
         */
        public static function lerCondicional(string $atributo, $condicao): array{
            try {

                $atributosPermitidos = ['id', 'nome', 'email', 'nivel'];
                
                if (!in_array($atributo, $atributosPermitidos)) {
                    throw new InvalidArgumentException("Atributo inválido: " . $atributo);
                }

                $query = "SELECT * FROM usuarios WHERE {$atributo} = :condicao";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindValue(':condicao', $condicao);
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Erro ao ler usuários: " . $e->getMessage());
                return [];
            }
        }

        /**
         * Acha o índice (id) de um usuário no banco de dados através do E-mail
         *
         * @param string $email
         * @return integer|null
         */
        public static function acharIndice(string $email): ?int{
            try {
                $query = "SELECT id FROM usuarios WHERE email = :email";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindValue(':email', trim($email), PDO::PARAM_STR);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_OBJ);
                return $resultado ? (int)$resultado->id : null;
                
            } catch (PDOException $e) {
                error_log("Erro ao buscar índice: " . $e->getMessage());
                return null;
            }
        }

        /**
         * Le um usuário específico através do id ou email. Caso o parametro passado seja do tipo int, será buscado diretamente no banco de dados pelo id. Caso seja do tipo String, será antes buscado o índice (id) daquele email na tabela usuários, e entao aplicada a recursao da funçao
         *
         * @param string|int $id
         * @return Usuario|null
         */
        public static function lerEspecifico(string|int $id): ?Usuario{
            try {
                if (is_int($id)) {

                    if ($id <= 0) {
                        return null;
                    }

                    $query = "SELECT * FROM usuarios WHERE id = :id";
                    $stmt = Conexao::getInstance()->prepare($query);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $resultado = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if ($resultado) {
                        return new Usuario(
                            (int)$resultado->id,
                            $resultado->nome,
                            $resultado->email,
                            $resultado->senha,
                            (int)$resultado->nivel
                        );
                    }
                    
                    return null;
                    
                } else{
                    $email = trim($id);
                    if (empty($email)) {
                        return null;
                    }
                    
                    $indice = static::acharIndice($email);
                    return $indice ? static::lerEspecifico($indice) : null;
                    
                }
                
            } catch (PDOException $e) {
                error_log("Erro ao ler usuário: " . $e->getMessage());
                return null;
            }
        }

        /**
         * Permite acessar os atributos do usuario
         *
         * @param string $name
         * @return mixed
         */
        public function __get(string $name): mixed {
            if (property_exists($this, $name)) {
                return $this->$name;
            }
            
            throw new Exception("Propriedade {$name} não existe");
        }

        /**
         * Cria a chave de usuario na sessao
         *
         * @return void
         */
        public function autenticar(){
            Sessao::criar('id_usuario', $this->id);
        }

    }

?>