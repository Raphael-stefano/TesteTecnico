<?php
namespace Teste;

use PHPUnit\Framework\TestCase;
use Testes\TestDatabase;
use Sistema\Modelo\Usuario;
use Core\Conexao;

class UsuarioTest extends TestCase
{

    protected function setUp(): void{

        Conexao::setInstance(TestDatabase::getConnection());

        $pdo = Conexao::getInstance();

        $pdo->exec('DELETE FROM usuarios');

        $pdo->exec("
            INSERT INTO usuarios (id, nome, email, senha, nivel) 
            VALUES (1, 'Teste Usuario', 'teste@usuario.com', 'senha123', 1)
        ");

        $pdo->exec("
            INSERT INTO usuarios (id, nome, email, senha, nivel) 
            VALUES (2, 'User Test', 'testando@usuario.com', 'senha321', 2)
        ");
    }

    /**
     * Testa se a funçao de ler é capaz de retornar corretamente um array de usuarios
     *
     * @return void
     */
    public function testLer(): void{
        $usuarios = Usuario::ler();
        $this->assertNotEmpty($usuarios);
        $this->assertEquals('Teste Usuario', $usuarios[0]['nome']);
    }

    /**
     * Testa se a funçao de ler condicional é capaz de retornar corretamente um array de usuarios corretamente filtrados
     *
     * @return void
     */
    public function testLerCondicional(): void{
        $usuarios = Usuario::lerCondicional('email', 'testando@usuario.com');
        $this->assertNotEmpty($usuarios);
        $this->assertEquals('User Test', $usuarios[0]['nome']);
    }

    /**
     * Testa se a funçao de achar indice consegue achar o indice de um usuário corretamente
     *
     * @return void
     */
    public function testAcharIndice(): void{
        $id = Usuario::acharIndice('testando@usuario.com');
        $this->assertIsInt($id);
        $this->assertEquals(2, $id);
    }

    /**
     * Testa se a funçao ler especifico funciona usando id
     *
     * @return void
     */
    public function testLerEspecificoPorId(): void{
        $usuario = Usuario::lerEspecifico(2);
        $this->assertInstanceOf(Usuario::class, $usuario);
        $this->assertEquals('User Test', $usuario->nome);
    }

    /**
     * Testa se a funçao ler especifico funciona usando email
     *
     * @return void
     */
    public function testLerEspecificoPorEmail(): void{
        $usuario = Usuario::lerEspecifico('testando@usuario.com');
        $this->assertInstanceOf(Usuario::class, $usuario);
        $this->assertEquals('User Test', $usuario->nome);
    }

    /**
     * Testa se a funçao autenticar corretamente seta o id do usuario como $_SESSION['id_usuario']
     *
     * @return void
     */
    public function testAutenticar(): void{
        $usuario = Usuario::lerEspecifico(1);
        $usuario->autenticar();

        $this->assertEquals($_SESSION['id_usuario'], $usuario->id);
    }
}
