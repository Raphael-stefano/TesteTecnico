<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Core\Helper;
    use Core\Mensagem;
    use Core\Sessao;
    use Sistema\Modelo\Produto;
    use Sistema\Modelo\Usuario;
    use Exception;
    use InvalidArgumentException;
use Sistema\Modelo\Categoria;

    class AdminControlador extends Controlador{
        protected $usuario;

        /**
         * Caso nao haja um usuário autenticado e acima no nível 2, redireciona para a página de login
         */
        public function __construct(){
            parent::__construct("Templates/Admin");

            if(Sessao::autenticado()){
                $this->usuario = Usuario::lerEspecifico(Sessao::carregar()->id_usuario);
            } else{
                $this->mensagem->alerta("Faça login para acessar o painel de controle!")->flash();
                Helper::redirecionar('/login');
            }

            if($this->usuario->nivel < 2){

                $this->mensagem->alerta("Infelizmente, voce nao tem autorizaçao para acessar esta área")->flash();
                Helper::redirecionar('/login');

            }

        }

        /**
         * página principal do dashboard
         *
         * @return void
         */
        public function dashboard(): void{
            if(isset($_POST['filtro'])){
                $filtro = $_POST['filtro'];
                if($filtro === ''){
                $produtos = Produto::ler();
                } else{
                    $produtos = Produto::lerCondicionalLike('nome', $filtro);
                }
            } else{
                $produtos = Produto::ler();
            }
            
            echo $this->template->renderizar("dashboard.html.twig", [
                'produtos' => $produtos
            ]);
        }

        /**
         * Renderiza o formuçário para cadastrar um produto
         *
         * @return void
         */
        public function novo(): void{
            $categorias = Categoria::ler();
            echo $this->template->renderizar("formulario_produto.html.twig", [
                'categorias' => $categorias,
                'action' => '/admin/salvar_novo',
                'header_icone' => 'bi bi-box-seam',
                'header_verbo' => 'Adicionar',
                'submeter_icone' => 'bi bi-floppy',
                'submeter_verbo' => 'Salvar',
                'limpar' => true
            ]);
        }

        /**
         * Envia o produto cadastrado pelo formulário para o banco de dados
         *
         * @return void
         */
        public function salvar(): void{
            try {
                $nome = isset($_POST['nome']) ? (string)$_POST['nome'] : '';
                $quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 0;
                $preco = isset($_POST['preco']) ? (int)$_POST['preco'] : 0;
                $id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;
                $sku = isset($_POST['sku']) ? (string)$_POST['sku'] : '';
                $descricao = isset($_POST['descricao']) ? (string)$_POST['descricao'] : '';

                if (empty($nome)) {
                    throw new InvalidArgumentException("Nome do produto é obrigatório");
                }
                if ($quantidade < 0) {
                    throw new InvalidArgumentException("Quantidade não pode ser negativa");
                }
                if ($preco <= 0) {
                    throw new InvalidArgumentException("Preço deve ser maior que zero");
                }
                if ($id_categoria <= 0) {
                    throw new InvalidArgumentException("Essa nao é uma categoria válida deve ser maior que zero");
                }

                $produto = new Produto(0, (string)$nome, $quantidade, $preco, $sku, $descricao, $id_categoria);
                $produto->salvar();
                $this->mensagem->sucesso("Produto salvo com sucesso!")->flash();
            } catch (Exception $e) {
                error_log($e->getMessage());
                $this->mensagem->erro($e->getMessage())->flash();
            } finally {
                Helper::redirecionar('/admin/dashboard');
            }
        }

        /**
         * Renderiza o formuçário para editar o produto
         *
         * @param [type] $id
         * @return void
         */
        public function editar($id): void {
            $produto = Produto::lerEspecifico((int)$id);
            $categorias = Categoria::ler();
            if ($produto) {
                echo $this->template->renderizar("formulario_produto.html.twig", [
                    'nome' => $produto->nome,
                    'sku' => $produto->sku,
                    'id_categoria' => $produto->id_categoria,
                    'preco' => $produto->preco,
                    'quantidade' => $produto->quantidade,
                    'descricao' => $produto->descricao,
                    'categorias' => $categorias,
                    'action' => '/admin/salvar_alteracoes/' . $id,
                    'header_icone' => 'bi bi-pencil',
                    'header_verbo' => 'Editar',
                    'submeter_icone' => 'bi bi-check2',
                    'submeter_verbo' => 'Atualizar',
                    'limpar' => false
                ]);
            } else {
                $this->mensagem->alerta("Produto não encontrado!")->flash();
                Helper::redirecionar('/admin/dashboard');
            }
        }

        /**
         * Envia o produto atualizado pelo formulário para o banco de dados
         *
         * @param [type] $id
         * @return void
         */
        public function salvarAlteracoes($id): void {
            try {
                $produto = Produto::lerEspecifico((int)$id);
                
                if (!$produto) {
                    throw new Exception("Produto não encontrado!");
                }
                
                $produto->nome = $_POST['nome'] ?? $produto->nome;
                $produto->quantidade = (int)($_POST['quantidade'] ?? $produto->quantidade);
                $produto->preco = (int)($_POST['preco'] ?? $produto->preco);
                $produto->id_categoria = (int)($_POST['id_categoria'] ?? $produto->id_categoria);
                $produto->sku = $_POST['sku'] ?? $produto->sku;
                $produto->descricao = $_POST['descricao'] ?? $produto->descricao;
                
                if (!$produto->salvarAlteracoes()) {
                    throw new Exception("Falha ao salvar as alterações no banco de dados");
                }
                
                $this->mensagem->sucesso("Produto atualizado com sucesso!")->flash();
                
            } catch (Exception $e) {
                $tipoMensagem = match(true) {
                    str_contains($e->getMessage(), 'não encontrado') => 'alerta',
                    default => 'erro'
                };
                
                $this->mensagem->$tipoMensagem($e->getMessage())->flash();
            } finally {
                Helper::redirecionar('/admin/dashboard');
            }
        }

        /**
         * Renderiza a página de confirmaçao para deletar o produto
         *
         * @param [type] $id
         * @return void
         */
        public function confirmarDeletar($id): void{
            $produto = Produto::lerEspecifico((int)$id);
            if ($produto) {
                echo $this->template->renderizar("confirmar_deletar.html.twig", [
                    'nome' => $produto->nome,
                    'sku' => $produto->sku,
                    'quantidade' => $produto->quantidade,
                    'preco' => $produto->preco,
                    'action' => '/admin/deletar/' . $produto->id,
                ]);
            }
        }

        /**
         * deleta um produto do banco de dados
         *
         * @param [type] $id
         * @return void
         */
        public function deletar($id): void {
            $produto = Produto::lerEspecifico((int)$id);
            if ($produto && Produto::deletar($id)) {
                $this->mensagem->sucesso("Produto deletado com sucesso!")->flash();
            } else {
                $this->mensagem->erro("Falha ao deletar o produto!")->flash();
            }
            Helper::redirecionar('/admin/dashboard');
        }

        /**
         * Encerra a sessao
         *
         * @return void
         */
        public function sair(): void{
            Sessao::deletar();
            $this->usuario = null;
            $this->mensagem->alerta("Voce saiu do painel de controle!")->flash();
            Helper::redirecionar('/login');
        }

    }

?>