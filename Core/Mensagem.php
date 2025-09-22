<?php

    namespace Core;

    /**
     * Classe que administra uma mensagem de texto
     * 
     * @author Raphael Stefano Barros Erbetta <raphaelestefano@email.com>
     */
    class Mensagem{
        private string $texto, $css;
        
        public function __construct(string $texto = "Mensagem padrao", string $css = ""){
            $this->texto = $texto;
            $this->css = $css;
        }

        public function __toString(){
            return $this->renderizar();
        }

        public function filtrar(): string{
            return strip_tags(trim($this->texto)); 
        }

        private function renderizar(): string{
            return "<div class='mensagem {$this->css}' role='alert'><span>{$this->filtrar($this->texto)}</span></div>";
        }

        public function alerta(string $mensagem = "Mensagem de alerta"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "mensagem-alerta";
            return $this;
        }

        public function sucesso(string $mensagem = "Mensagem de sucesso"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "mensagem-sucesso";
            return $this;
        }

        public function erro(string $mensagem = "Mensagem de erro"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "mensagem-erro";
            return $this;
        }

        public function getTexto(): string{
            return $this->texto;
        }

        public function setTexto(string $texto): void{
            $this->texto = $texto;
        }

        public function flash(): void{
            $sessao = new Sessao();
            $sessao->criar('flash', $this);
        }
    }

?>