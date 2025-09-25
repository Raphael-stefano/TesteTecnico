<?php

use PHPUnit\Framework\TestCase;
use Core\Helper;

class HelperTest extends TestCase
{
    /**
     * Testa se a funçao de verificar senha é capaz de comparar corretamente uma senha gerada pelo método de gerar senha da mesma classe
     *
     * @return void
     */
    public function testGerarEVerificarSenha(){
        $hash = Helper::gerarSenha("senha123");
        $this->assertTrue(Helper::verificarSenha("senha123", $hash));
        $this->assertFalse(Helper::verificarSenha("errada", $hash));
    }

    /**
     * Testa se a funçao de formatar valor é capaz de formatar os valores float no formato brasileiro
     *
     * @return void
     */
    public function testFormatarValor(){
        $this->assertEquals("1.234,57", Helper::formatarValor(1234.567));
    }

    /**
     * Verifica se a funçao formatarMOnetario é capaz de transformar um Float em um número de valor monetario
     *
     * @return void
     */
    public function testFormatarMonetario(){
        $this->assertEquals("R$ 1.234,50", Helper::formatarMonetario(1234.5));
    }
}
