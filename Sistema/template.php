<?php

    namespace Sistema;
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;
    use Twig\Lexer;
    use Twig\TwigFunction;
    use Core\Helper;

    class Template{
        private Environment $twig;

        /**
         * 
         *
         * @param string $diretorio caminho do diretório onde estao as templates
         */
        public function __construct(string $diretorio){
            $loader = new FilesystemLoader($diretorio);
            $this->twig = new Environment($loader);

            $lexer = new Lexer($this->twig, array($this->helpers()));
            $this->twig->setLexer($lexer);
        }

        /**
         * renderiza uma template
         *
         * @param string $view
         * @param array $dados
         * @return string
         */
        public function renderizar(string $view, array $dados): string{
            return $this->twig->render($view, $dados);
        }

        /**
         * Funçoes do helper acessiveis no front-end
         *
         * @return void
         */
        private function helpers(): void{
            array(
                $this->twig->addFunction(
                    new TwigFunction("url", function(string $url = ""){
                        return Helper::url($url);
                    })   
                ),
                $this->twig->addFunction(
                    new TwigFunction("flash", function(){
                        return Helper::flash();
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("redirecionar", function(string $url){
                        return Helper::redirecionar($url);
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("formatarMonetario", function(float $valor){
                        return Helper::formatarMonetario($valor);
                    })
                )
            );
        }

    }
?>