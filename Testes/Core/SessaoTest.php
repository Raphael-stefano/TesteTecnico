<?php

use PHPUnit\Framework\TestCase;
use Core\Sessao;
use Core\Mensagem;

class SessaoTest extends TestCase
{
    protected function setUp(): void{
        $_SESSION = [];
    }

    /**
     * Verifica se o objeto sesao consegue criar uma chave e seu valor, e se isso gera o valor certo para a chave certa. Faz isso criando uma chave e valor com o método da classe, depois checando se ele está setado, e por fim comparando o método checar com a chave
     *
     * @return void
     */
    public function testCriarECarregar(){
        Sessao::criar("teste", "valor");
        $this->assertTrue(Sessao::checar("teste"));

        $dados = Sessao::carregar();
        $this->assertEquals("valor", $dados->teste);
    }

    /**
     * Testa a geraçao de mensagem flash
     *
     * @return void
     */
    public function testFlash(){
        $sessao = new Sessao();
        $msg = new Mensagem("Aviso!");
        $msg->flash();

        $flash = $sessao->flash();
        $this->assertInstanceOf(Mensagem::class, $flash);
        $this->assertEquals("Aviso!", $flash->getTexto());
    }
}
