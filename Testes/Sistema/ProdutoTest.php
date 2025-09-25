<?php


use PHPUnit\Framework\TestCase;
use Testes\TestDatabase;
use Sistema\Modelo\Produto;
use Core\Conexao;

class ProdutoTest extends TestCase
{
    protected function setUp(): void{

        Conexao::setInstance(TestDatabase::getConnection());

        $pdo = Conexao::getInstance();

        $pdo->exec('DELETE FROM categorias');

        $pdo->exec("INSERT INTO categorias (id, nome) VALUES (1, 'Categoria Teste')");
        $pdo->exec("INSERT INTO categorias (id, nome) VALUES (2, 'Test Category')");

        $pdo->exec('DELETE FROM produtos');

        $pdo->exec("
            INSERT INTO produtos (id, nome, quantidade, preco, sku, descricao, id_categoria) 
            VALUES (1, 'Produto Teste', 10, 1000, 'SKU123', 'Descrição teste', 1)
        ");

        $pdo->exec("
            INSERT INTO produtos (id, nome, quantidade, preco, sku, descricao, id_categoria) 
            VALUES (2, 'Test Product', 15, 1500, 'SKU321', 'Test description', 2)
        ");
    }

    /**
     * Testa se a funçao de salvar é capaz de salvar corretamente um novo produto. A funçao cria um produto novo, salva ele e tenta encontrálo com a funçao ler condicional.
     *
     * @return void
     */
    public function testSalvar(): void{
        $produto = new Produto(0, "Novo Produto", 5, 500, "SKU999", "Descrição nova", 1);
        $this->assertTrue($produto->salvar());

        $produtos = Produto::lerCondicional("nome", "Novo Produto");
        $this->assertNotEmpty($produtos);
        $this->assertEquals("Novo Produto", $produtos[0]['nome']);
    }

    /**
     * Testa se a funçao de salvar é capaz de salvar corretamente um produto alterado. A funçao altera um produto existente, salva ele alterado, tenta encontrálo com a funçao ler condicional e verifica se a alteraçao foi aplicada.
     *
     * @return void
     */
    public function testSalvarAlteracoes(): void{
        $produto = Produto::lerEspecifico(1);
        $produto->nome = "Produto Alterado";
        $this->assertTrue($produto->salvarAlteracoes());

        $produtoAlterado = Produto::lerEspecifico(1);
        $this->assertEquals("Produto Alterado", $produtoAlterado->nome);
    }

    /**
     * Testa a funçao de deletar um produto
     *
     * @return void
     */
    public function testDeletar(): void{
        $produto = Produto::lerEspecifico(1);
        $this->assertTrue(Produto::deletar($produto->id));

        $produtoDeletado = Produto::lerEspecifico(1);
        $this->assertNull($produtoDeletado);
    }

    /**
     * Testa se a funçao de ler é capaz de retornar corretamente um array de produtos
     *
     * @return void
     */
    public function testLer(): void{
        $produtos = Produto::ler();
        $this->assertNotEmpty($produtos);
    }

    /**
     * Testa se a funçao de ler condicional é capaz de retornar corretamente um array de produtos corretamente filtrados
     *
     * @return void
     */
    public function testLerCondicional(): void{
        $produtos = Produto::lerCondicional("nome", "Produto Teste");
        $this->assertNotEmpty($produtos);
    }

    /**
     * Testa se a funçao de ler condicional usando like é capaz de retornar corretamente um array de produtos corretamente filtrados atraves do like
     *
     * @return void
     */
    public function testLerCondicionalLike(): void{
        $produtos = Produto::lerCondicionalLike("nome", "Teste");
        $this->assertNotEmpty($produtos);
    }

    /**
     * Testa se a funçao de achar indice consegue achar o indice de um produto corretamente
     *
     * @return void
     */
    public function testAcharIndice(): void{
        $id = Produto::acharIndice("Produto Teste");
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    /**
     * Testa se a funçao ler especifico funciona usando id
     *
     * @return void
     */
    public function testLerEspecificoPorId(): void{
        $produto = Produto::lerEspecifico(1);
        $this->assertInstanceOf(Produto::class, $produto);
        $this->assertEquals("Produto Teste", $produto->nome);
    }

    /**
     * Testa se a funçao ler especifico funciona usando nome
     *
     * @return void
     */
    public function testLerEspecificoPorNome(): void{
        $produto = Produto::lerEspecifico("Produto Teste");
        $this->assertInstanceOf(Produto::class, $produto);
        $this->assertEquals("Produto Teste", $produto->nome);
    }
}
