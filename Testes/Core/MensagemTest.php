<?php

use PHPUnit\Framework\TestCase;
use Core\Mensagem;
use Core\Sessao;

class MensagemTest extends TestCase
{
    /**
     * Testa se a menagem gerada ao criar um objeto é a mensagem padrao
     *
     * @return void
     */
    public function testMensagemPadrao(){
        $msg = new Mensagem();
        $this->assertEquals("Mensagem padrao", $msg->getTexto());
    }

    /**
     * Testa de o método filtrar é capaz de retornar uma mensagem livre de tags html
     *
     * @return void
     */
    public function testMensagemFiltrarRemoveHtml(){
        $msg = new Mensagem("<b>Olá</b> mundo!");
        $this->assertEquals("Olá mundo!", $msg->filtrar());
    }

    /**
     * Testa se a mensagem de alerta recebe as propriedades de uma mensagem de alerta
     *
     * @return void
     */
    public function testMensagemAlerta(){
        $msg = new Mensagem();
        $msg->alerta("Mensagem de alerta");
        $this->assertEquals("Mensagem de alerta", $msg->getTexto());
        $this->assertEquals("mensagem-alerta", $msg->getCss());
    }

    /**
     * Testa se a mensagem de sucesso recebe as propriedades de uma mensagem de sucesso
     *
     * @return void
     */
    public function testMensagemSucesso(){
        $msg = new Mensagem();
        $msg->sucesso("Mensagem de sucesso");
        $this->assertEquals("Mensagem de sucesso", $msg->getTexto());
        $this->assertEquals("mensagem-sucesso", $msg->getCss());
    }

    /**
     * Testa se a mensagem de erro recebe as propriedades de uma mensagem de erro
     *
     * @return void
     */
    public function testMensagemErro(){
        $msg = new Mensagem();
        $msg->erro("Mensagem de erro");
        $this->assertEquals("Mensagem de erro", $msg->getTexto());
        $this->assertEquals("mensagem-erro", $msg->getCss());
    }
}