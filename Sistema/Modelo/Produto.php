<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
    use PDO;
    use PDOException;
    use Exception;
    use InvalidArgumentException;

    class Produto {
        protected int $id;
        protected string $nome;
        protected int $quantidade;
        protected int $preco;
        protected string $sku;
        protected string $descricao;
        protected int $id_categoria;

        public function __construct(int $id = 0, string $nome = '', int $quantidade = 0, int $preco = 0, string $sku = '', string $descricao = '', int $id_categoria = 0) {
            $this->id = $id;
            $this->nome = $nome;
            $this->quantidade = $quantidade;
            $this->preco = $preco;
            $this->sku = $sku;
            $this->descricao = $descricao;
            $this->id_categoria = $id_categoria;
        }

        /**
         * Aplica um Select sem usar nenhuma condicional
         *
         * @return array
         */
        public static function ler(): array {
            try {
                $query = "SELECT * FROM produtos";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Erro ao ler produtos: " . $e->getMessage());
                return [];
            }
        }

        /**
         * Aplica um Select utilizando uma condicional
         *
         * @param string $atributo
         * @param mixed $condicao
         * @return array
         */
        public static function lerCondicional(string $atributo, $condicao): array {
            try {
                $atributosPermitidos = ['id', 'nome', 'quantidade', 'preco', 'sku', 'descricao', 'id_categoria'];
                if (!in_array($atributo, $atributosPermitidos)) {
                    throw new InvalidArgumentException("Atributo inválido: " . $atributo);
                }

                $query = "SELECT * FROM produtos WHERE {$atributo} = :condicao";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindValue(':condicao', $condicao);
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Erro ao ler produtos: " . $e->getMessage());
                return [];
            }
        }

        /**
         * Aplica um Select utilizando uma condicional com like
         *
         * @param string $atributo
         * @param mixed $condicao
         * @return array
         */
        public static function lerCondicionalLike(string $atributo, string $condicao): array {
            try {
                $atributosPermitidos = ['nome', 'descricao', 'sku'];
                if (!in_array($atributo, $atributosPermitidos)) {
                    throw new InvalidArgumentException("Atributo inválido: " . $atributo);
                }

                $query = "SELECT * FROM produtos WHERE {$atributo} like :condicao";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindValue(':condicao', '%' . $condicao . '%');
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Erro ao ler produtos: " . $e->getMessage());
                return [];
            }
        }

        /**
         * Acha o índice (id) de um produto no banco de dados através do nome
         *
         * @param string $nome
         * @return integer|null
         */
        public static function acharIndice(string $nome): ?int {
            try {
                $query = "SELECT id FROM produtos WHERE nome = :nome";
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
         * Lê um produto específico através do id ou nome. Caso o parametro passado seja do tipo int, será buscado diretamente no banco de dados pelo id. Caso seja do tipo String, será antes buscado o índice (id) daquele nome na tabela produtos, e então aplicada a recursão da função
         *
         * @param string|int $id
         * @return Produto|null
         */
        public static function lerEspecifico(string|int $id): ?Produto {
            try {
                if (is_int($id)) {
                    if ($id <= 0) {
                        return null;
                    }

                    $query = "SELECT * FROM produtos WHERE id = :id";
                    $stmt = Conexao::getInstance()->prepare($query);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $resultado = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if ($resultado) {
                        return new Produto(
                            (int)$resultado->id,
                            $resultado->nome,
                            (int)$resultado->quantidade,
                            (int)$resultado->preco,
                            $resultado->sku,
                            $resultado->descricao,
                            (int)$resultado->id_categoria
                        );
                    }
                    
                    return null;
                } else {
                    $nome = trim($id);
                    if (empty($nome)) {
                        return null;
                    }
                    
                    $indice = static::acharIndice($nome);
                    return $indice ? static::lerEspecifico($indice) : null;
                }
            } catch (PDOException $e) {
                error_log("Erro ao ler produto: " . $e->getMessage());
                return null;
            }
        }

        /**
         * Permite acessar os atributos do produto
         *
         * @param string $name
         * @return mixed
         */
        public function __get(string $name): mixed {
            try{
                if($name === "categoria"){
                    return Categoria::lerEspecifico($this->id_categoria);
                }
    
                if (isset($this->$name)) {
                    return $this->$name;
                }
            } catch(Exception $e){
                error_log("Erro ao encontrar propriedade: " . $e->getMessage());
            }
            return null;
        }

        /**
         * Permite definir o valor de propriedades protegidas
         *
         * @param string $name
         * @param mixed $value
         * @return void
         */
        public function __set(string $name, $value): void {
            if (property_exists($this, $name)) {
                switch ($name) {
                    case 'nome':
                        $this->nome = (string)$value;
                        break;
                    case 'quantidade':
                        $this->quantidade = (int)$value;
                        break;
                    case 'preco':
                        $this->preco = (int)$value;
                        break;
                    case 'id':
                        if ((int)$value > 0) {
                            $this->id = (int)$value;
                        }
                        break;
                    case 'sku':
                        $this->sku = (string)$value;
                        break;
                    case 'descricao':
                        $this->descricao = (string)$value;
                        break;
                    case 'id_categoria':
                        $this->id_categoria = (int)$value;
                        break;
                }
            } else {
                throw new Exception("Propriedade {$name} não existe");
            }
        }

        /**
         * Cria e salva um novo produto no banco de dados
         *
         * @return boolean
         */
        public function salvar() : bool{
            $pdo = Conexao::getInstance();
            try {
                if (empty($this->nome) || !is_string($this->nome) || strlen($this->nome) > 255) {
                    throw new InvalidArgumentException("Nome do produto é obrigatório");
                    }
                if ($this->quantidade < 0) {
                    throw new InvalidArgumentException("Quantidade não pode ser negativa");
                }
                if ($this->preco <= 0) {
                    throw new InvalidArgumentException("Preço deve ser maior que zero");
                }
                if ($this->id_categoria <= 0) {
                    throw new InvalidArgumentException("Essa nao é uma categoria válida");
                }

                $pdo->beginTransaction();

                $query = "insert into produtos (nome, quantidade, preco, sku, descricao, id_categoria) values (:nome, :quantidade, :preco, :sku, :descricao, :id_categoria)";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':nome', $this->nome, PDO::PARAM_STR);
                $stmt->bindValue(':quantidade', $this->quantidade, PDO::PARAM_INT);
                $stmt->bindValue(':preco', $this->preco, PDO::PARAM_INT);
                $stmt->bindValue(':sku', $this->sku, PDO::PARAM_STR);
                $stmt->bindValue(':descricao', $this->descricao, PDO::PARAM_STR);
                $stmt->bindValue(':id_categoria', $this->id_categoria, PDO::PARAM_INT);

                $sucesso = $stmt->execute();

                if ($sucesso) {
                    $this->id = (int)$pdo->lastInsertId();
                    $pdo->commit();
                } else {
                    $pdo->rollBack();
                }

                return $sucesso;

            } catch (PDOException $e){
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Erro ao salvar produto: " . $e->getMessage());
                return false;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Erro ao salvar produto: " . $e->getMessage());
                return false;
            }
        }

        /**
         * Atualiza o produto no banco de dados, salvando as alteraçoes feitas nele
         *
         * @return bool
         */
        public function salvarAlteracoes(): bool {
            $pdo = Conexao::getInstance();
            try {
                if ($this->id <= 0) {
                    throw new Exception("ID do produto inválido");
                }
                if (empty($this->nome) || !is_string($this->nome) || strlen($this->nome) > 255) {
                    throw new InvalidArgumentException("Nome do produto é obrigatório e deve ter até 255 caracteres");
                }
                if ($this->quantidade < 0) {
                    throw new InvalidArgumentException("Quantidade não pode ser negativa");
                }
                if ($this->preco <= 0) {
                    throw new InvalidArgumentException("Preço deve ser maior que zero");
                }
                if ($this->id_categoria <= 0) {
                    throw new InvalidArgumentException("Essa nao é uma categoria válida");
                }

                $pdo->beginTransaction();

                $query = "UPDATE produtos SET nome = :nome, quantidade = :quantidade, preco = :preco, sku = :sku, descricao = :descricao, id_categoria = :id_categoria WHERE id = :id";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
                $stmt->bindValue(':nome', $this->nome, PDO::PARAM_STR);
                $stmt->bindValue(':quantidade', $this->quantidade, PDO::PARAM_INT);
                $stmt->bindValue(':preco', $this->preco, PDO::PARAM_INT);
                $stmt->bindValue(':sku', $this->sku, PDO::PARAM_STR);
                $stmt->bindValue(':descricao', $this->descricao, PDO::PARAM_STR);
                $stmt->bindValue(':id_categoria', $this->id_categoria, PDO::PARAM_INT);

                $sucesso = $stmt->execute();

                if ($sucesso) {
                    $pdo->commit();
                } else {
                    $pdo->rollBack();
                }

                return $sucesso;
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Erro ao editar produto: " . $e->getMessage());
                return false;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Erro ao editar produto: " . $e->getMessage());
                return false;
            }
        }

        /**
         * Deleta um produto do banco de dados atraves do id
         *
         * @param int $id
         * @return bool
         */
        public static function deletar(int $id): bool {
            $pdo = Conexao::getInstance();
            try {
                if ($id <= 0) {
                    throw new InvalidArgumentException("ID inválido");
                }

                $pdo->beginTransaction();

                $query = "DELETE FROM produtos WHERE id = :id";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $sucesso = $stmt->execute();

                if ($sucesso && $stmt->rowCount() > 0) {
                    $pdo->commit();
                    return true;
                } else {
                    $pdo->rollBack();
                    return false;
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Erro ao deletar produto: " . $e->getMessage());
                return false;
            } catch (InvalidArgumentException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log("Erro ao deletar produto: " . $e->getMessage());
                return false;
            }
        }
    }

?>