<?php


use PHPUnit\Framework\TestCase;
use Testes\TestDatabase;
use Sistema\Modelo\Categoria;
use Core\Conexao;

class CategoriaTest extends TestCase
{

    protected function setUp(): void{
        Conexao::setInstance(TestDatabase::getConnection());

        $pdo = Conexao::getInstance();

        $pdo->exec('DELETE FROM categorias');

        $pdo->exec("INSERT INTO categorias (id, nome) VALUES (1, 'Categoria Teste')");
        $pdo->exec("INSERT INTO categorias (id, nome) VALUES (2, 'Test Category')");
    }

    /**
     * Testa se a funçao de ler é capaz de retornar corretamente um array de categorias
     *
     * @return void
     */
    public function testLer(): void{
        $categorias = Categoria::ler();
        $this->assertNotEmpty($categorias);
        $this->assertEquals('Categoria Teste', $categorias[0]['nome']);
    }

    /**
     * Testa se a funçao de ler condicional é capaz de retornar corretamente um array de categorias corretamente filtradas
     *
     * @return void
     */
    public function testLerCondicional(): void{
        $categorias = Categoria::lerCondicional('nome', 'Test Category');
        $this->assertNotEmpty($categorias);
        $this->assertEquals('Test Category', $categorias[0]['nome']);
    }

    /**
     * Testa se a funçao de achar indice consegue achar o indice de uma categoria corretamente
     *
     * @return void
     */
    public function testAcharIndice(): void{
        $id = Categoria::acharIndice('Categoria Teste');
        $this->assertIsInt($id);
        $this->assertEquals(1, $id);
    }

    /**
     * Testa se a funçao ler especifico funciona usando id
     *
     * @return void
     */
    public function testLerEspecificoPorId(): void{
        $categoria = Categoria::lerEspecifico(2);
        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals('Test Category', $categoria->nome);
    }

    /**
     * Testa se a funçao ler especifico funciona usando nome
     *
     * @return void
     */
    public function testLerEspecificoPorNome(): void{
        $categoria = Categoria::lerEspecifico('Test Category');
        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals('Test Category', $categoria->nome);
    }
}
