# teste_tecnico

Esta aplicaçao foi desenvolvida utilizando PhP com pecee/simplerouter e twig template.

# Tela de login

A tela de login pede o E-mail e a Senha do usuário para que ele possa acessar o site e/ou o dashboard.

# Níveis de usuário

Usuários de nível 1 conseguem acessar apenas o site, nao possuindo acesso ao dashboard. O site nao foi desenvolvido para essa aplicaçao, pois foi pedido apenas o dashboard. No entanto, foi criada a rota para o site para fins de demonstrar o redirecionamento. Usuários de nível 2 conseguem acessar o dashboard, portanto aqueles que logarem em seu usuário e possuírem o nível 2 serao redirecionados para o dashboard. Caso as credenciais estejam erradas, será renderizada uma mensagem de alerta.

# Tabela dashboard

A tabela dashboard apresenta os nomes dos produtos, seus respectivos preços e quantiddades, e os links para editar ou apagar os produtos. No topo da tabela, há um link para o formulário de cadastro de produto e uma barra de pesquisa para filtrar nomes de produtos. A barra está funcional, sendo o ícone de lupa o botao

# Formulário de cadastro de produto

O formulário de cadastro de produto apresenta os campos para todas as informaçoes do produto, incluindo um select que apresenta todas as categorias de produto existentes no banco de dados. O botao de cancelar e o ícone de "x" redirecionam para a tabela do dashboard, e o botao "limpar" recarrega a página sem as informaçoes que já haviam sido escritas.

# Formulário de ediçao de produto

O formulário de ediçao de produto recebe o produto que se pretende editar através do ID, inserindo as informaçoes do produto nos campos do formulário. O funcionamento é praticamente identico ao do formulário de cadastro, no entanto nao há botao de limpar.

Ambos as açoes de cadastrar e de editar terminam renderizando uma mensagem de Sucesso acima da tabela do dashboard.

# Confirmaçao de exclusao

Ao clicar em excluir um produto, o usuário é redirecionado a uma página de confirmaçao da exclusao do produto. Nesta página, sao exibidas algumas informaçoes do produto e apresentados avisos sobre a irreversibilidade da açao.
