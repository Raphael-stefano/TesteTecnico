<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
    use PDO;
    use PDOException;
    use Exception;
    use InvalidArgumentException;
    use Core\Sessao;

    class Categoria{
        protected int $id;
        protected string $nome;
        
        public function __construct(int $id = 0, string $nome = ''){
            $this->id = $id;
            $this->nome = $nome;
        }

        /**
         * Aplica um Select sem o uso de nenhuma condicional
         *
         * @return array
         */
        public static function ler(): array{
            try {
                $query = "SELECT * FROM categorias";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Erro ao ler categorias: " . $e->getMessage());
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

                $atributosPermitidos = ['id', 'nome'];
                
                if (!in_array($atributo, $atributosPermitidos)) {
                    throw new InvalidArgumentException("Atributo inválido: " . $atributo);
                }

                $query = "SELECT * FROM categorias WHERE {$atributo} = :condicao";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindValue(':condicao', $condicao);
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Erro ao ler categorias: " . $e->getMessage());
                return [];
            }
        }

        /**
         * Acha o índice (id) de uma categoria no banco de dados através do nome
         *
         * @param string $email
         * @return integer|null
         */
        public static function acharIndice(string $nome): ?int{
            try {
                $query = "SELECT id FROM categorias WHERE nome = :nome";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindValue(':nome', trim($nome), PDO::PARAM_STR);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_OBJ);
                return $resultado ? (int)$resultado->id : null;
                
            } catch (PDOException $e) {
                error_log("Erro ao buscar índice: " . $e->getMessage());
                return null;
            }
        }

        /**
         * Le um usuário específico através do id ou nome. Caso o parametro passado seja do tipo int, será buscado diretamente no banco de dados pelo id. Caso seja do tipo String, será antes buscado o índice (id) daquele nome na tabela usuários, e entao aplicada a recursao da funçao
         *
         * @param string|int $id
         * @return Usuario|null
         */
        public static function lerEspecifico(string|int $id): ?Categoria{
            try {
                if (is_int($id)) {

                    if ($id <= 0) {
                        return null;
                    }

                    $query = "SELECT * FROM categorias WHERE id = :id";
                    $stmt = Conexao::getInstance()->prepare($query);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $resultado = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if ($resultado) {
                        return new Categoria(
                            (int)$resultado->id,
                            $resultado->nome
                        );
                    }
                    
                    return null;
                    
                } else{
                    $nome = trim($id);
                    if (empty($nome)) {
                        return null;
                    }
                    
                    $indice = static::acharIndice($nome);
                    return $indice ? static::lerEspecifico($indice) : null;
                    
                }
                
            } catch (PDOException $e) {
                error_log("Erro ao ler usuário: " . $e->getMessage());
                return null;
            }
        }

        /**
         * Permite acessar os atributos da categoria
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

    }

?>