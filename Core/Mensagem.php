<?php

    namespace Core;

    /**
     * Classe que administra uma mensagem de texto
     * 
     * @author Raphael Stefano Barros Erbetta <raphaelestefano@email.com>
     */
    class Mensagem{
        private string $texto, $css;
        
        /**
         * Undocumented function
         *
         * @param string $texto texto da mensagem
         * @param string $css classe css da mensagem
         */
        public function __construct(string $texto = "Mensagem padrao", string $css = ""){
            $this->texto = $texto;
            $this->css = $css;
        }

        public function __toString(){
            return $this->renderizar();
        }

        /**
         * remove tags html e espaços vazios no início e final do texto
         *
         * @return string
         */
        public function filtrar(): string{
            return strip_tags(trim($this->texto)); 
        }

        /**
         * renderiza a mensagem
         *
         * @return string
         */
        private function renderizar(): string{
            return "<div class='mensagem {$this->css}' role='alert'><span>{$this->filtrar($this->texto)}</span></div>";
        }

        /**
         * mensagem com estilo alerta
         *
         * @param string $mensagem
         * @return Mensagem
         */
        public function alerta(string $mensagem = "Mensagem de alerta"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "mensagem-alerta";
            return $this;
        }

        /**
         * mensagem com estilo sucesso
         *
         * @param string $mensagem
         * @return Mensagem
         */
        public function sucesso(string $mensagem = "Mensagem de sucesso"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "mensagem-sucesso";
            return $this;
        }

        /**
         * mensagem com estilo erro
         *
         * @param string $mensagem
         * @return Mensagem
         */
        public function erro(string $mensagem = "Mensagem de erro"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "mensagem-erro";
            return $this;
        }

        /**
         * retorna o texto da mensagem
         *
         * @return string
         */
        public function getTexto(): string{
            return $this->texto;
        }

        /**
         * altera o texto da mensagem
         *
         * @param string $texto
         * @return void
         */
        public function setTexto(string $texto): void{
            $this->texto = $texto;
        }

        /**
         * seta a si mesmo como flash na sessao
         *
         * @return void
         */
        public function flash(): void{
            $sessao = new Sessao();
            $sessao->criar('flash', $this);
        }
    }

?>