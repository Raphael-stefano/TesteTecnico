## Sumário
- [Descrição básica do Projeto](#teste_tecnico)
- [Tela de Login](#tela-de-login)
- [Níveis de Usuário](#níveis-de-usuário)
- [Tabela Dashboard](#tabela-dashboard)
- [Formulário de Cadastro de Produto](#formulário-de-cadastro-de-produto)
- [Formulário de Edição de Produto](#formulário-de-edição-de-produto)
- [Confirmação de Exclusão de Produto](#confirmação-de-exclusão)
- [Banco de Dados](#banco-de-dados)
- [Testes Unitários](#testes-unitários)
- [Tecnologias e Versões (para compatibilidade)](#tecnologias-e-versões)

# teste_tecnico

Esta aplicaçao foi desenvolvida utilizando PhP (8.2.0, composer 2.8.2) com pecee/simplerouter e twig template. A aplicaçao é rodada localmente utilizando o Xampp

# Tela de login

A tela de login pede o E-mail e a Senha do usuário para que ele possa acessar o site e/ou o dashboard.

![Tela de Login](https://raw.githubusercontent.com/Raphael-stefano/teste_tecnico/main/Imagens%20da%20tela/tela_de_login.png)

# Níveis de usuário

Usuários de nível 1 conseguem acessar apenas o site, nao possuindo acesso ao dashboard. O site nao foi desenvolvido para essa aplicaçao, pois foi pedido apenas o dashboard. No entanto, foi criada a rota para o site para fins de demonstrar o redirecionamento. Usuários de nível 2 conseguem acessar o dashboard, portanto aqueles que logarem em seu usuário e possuírem o nível 2 serao redirecionados para o dashboard. Caso as credenciais estejam erradas, será renderizada uma mensagem de alerta.

# Tabela dashboard

A tabela dashboard apresenta os nomes dos produtos, seus respectivos preços e quantiddades, e os links para editar ou apagar os produtos. No topo da tabela, há um link para o formulário de cadastro de produto e uma barra de pesquisa para filtrar nomes de produtos. A barra está funcional, sendo o ícone de lupa o botao

![Dashboard](https://raw.githubusercontent.com/Raphael-stefano/teste_tecnico/main/Imagens%20da%20tela/dashboard.png)

# Formulário de cadastro de produto

O formulário de cadastro de produto apresenta os campos para todas as informaçoes do produto, incluindo um select que apresenta todas as categorias de produto existentes no banco de dados. O botao de cancelar e o ícone de "x" redirecionam para a tabela do dashboard, e o botao "limpar" recarrega a página sem as informaçoes que já haviam sido escritas.

![Formulário para cadastro de produto](https://raw.githubusercontent.com/Raphael-stefano/teste_tecnico/main/Imagens%20da%20tela/cadastro_de_produto.png)

# Formulário de ediçao de produto

O formulário de ediçao de produto recebe o produto que se pretende editar através do ID, inserindo as informaçoes do produto nos campos do formulário. O funcionamento é praticamente identico ao do formulário de cadastro, no entanto nao há botao de limpar.

Ambos as açoes de cadastrar e de editar terminam renderizando uma mensagem de Sucesso acima da tabela do dashboard.

![Formulário para ediçao de produto](https://raw.githubusercontent.com/Raphael-stefano/teste_tecnico/main/Imagens%20da%20tela/edicao_de_produto.png)

# Confirmaçao de exclusao

Ao clicar em excluir um produto, o usuário é redirecionado a uma página de confirmaçao da exclusao do produto. Nesta página, sao exibidas algumas informaçoes do produto e apresentados avisos sobre a irreversibilidade da açao.

![Tela de confirmaçao de exclusao](https://raw.githubusercontent.com/Raphael-stefano/teste_tecnico/main/Imagens%20da%20tela/exclusao_de_produto.png)

# Banco de Dados

![Diagrama do Banco de Dados](https://raw.githubusercontent.com/Raphael-stefano/teste_tecnico/main/Banco%20de%20Dados/diagrama.png)

O banco de dados foi desenvolvido utilizando o MySQL (distribuiçao 10.4.27-MariaDB, que vem junto com o Xampp).

No banco de dados do projeto, foram criadas tabelas para usuário, produto e categoria. Categoria foi desenvolvida apenas para ser usada no formulário do produto. Usuário foi desenvolvido para a validaçao na tela de login. Produto foi mais desenvolvido, pois foi o que mais foi pedido nos requisitos do projeto.

O atributo usado para a validaçao foi o E-mail do usuário. As senhas estao devidamente criptografadas. O preço do produto foi definido como INT ao invés de float pois float pode ser impreciso. Para maior precisao, foi usado INT para representar centavos, sendo feita a formataçao e divisao por 100 para representar visualmente os preços.

# Testes unitários

Este projeto utiliza PHPUnit para testes unitários. 
Os testes cobrem:
- CRUD completo de Produtos
- Leitura condicional e específica de Categorias e Usuários
- Validação de helpers utilitários
- Funcionamento dos objetos Sessão e Mensagem

Para executar os testes:
```bash
php vendor/bin/phpunit
```

# Tecnologias e versoes (para compatibilidade)

- PHP: 8.2.0 (via Xampp)
- Banco de Dados: MariaDB 10.4.27 (compatível com MySQL, também via Xampp)
- Servidor Web: Apache (via Xampp)
