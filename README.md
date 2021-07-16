# App Max - Processo seletivo.

Este projeto foi proposto como etapa de seleção para Desenvolvedor Full Stack.

# Sobre o projeto!

O desafio consiste em desenvolver um sistema em laravel que possibilite gerenciar um estoque, com os seguintes requisitos: 

* Um CRUD para os produtos com SKU para identificação.
* Uma tela para adicionar produtos ao estoque.
* Uma tela para dar baixa em produtos que serão enviados aos clientes.

O sistema deverá possuir uma API, disponível para fazer as movimentações do estoque com 2 endpoints:
  * /api/baixar-produtos
  * /api/adicionar-produtos
* Um relatório de produtos movimentados por dia com: 
  * Quantos e quais produtos foram adicionados ao estoque.
  * Quantos e quais produtos foram removidos do estoque.
  * Se a adição/remoção foi feita via sistema ou via API.
  * Aviso de estoque baixo quando um produto possuir menos de 100 unidades no estoque. 

<strong>O sistema deverá estar protegido por um sistema de login!</strong>

# Estrutura e tecnologias
Para a construção do projeto, foi utlizado as seguintes tecnologias:

* **Ambiente de desenvolvimento**
    * Docker/docker-compose
        * PHP 8.0
        * Node 14-lts
        * Nginx 1.21.0
* **Font-end.**
    * VueJs
        * Vuex
        * Vue-route
    * Bootstrap 4
        * SASS
    * Axios
* **Back-end.**
    * Lavavel Versão 8
    * MySql 5.7

# Iniciando projeto

Para iniciar o projeto, não é obrigatório a utilização do docker, porém se faz recomendado por simular ao maximo a ambiente de produção.

- Clone o repositorio:

```bash
  git clone https://github.com/GenesesLopes/test-appmaxi.git
```

- Navegue até a pasta que acabou de clonar:

```bash
  cd test-appmax
```

- Para subir o container desejado, basta executar o comando (Windows, ou Linux):

```sh
docker-compose up -d nomeDoContainer
```
- Ou pode Subir todos de uma vez: 
```sh
docker-compose up -d 
```
- Containers disponiveis; `api-appmax`, `nginx-appmax` e `db-appmax`.

Para logar no sistema, utilize as credencias já pre-cadastradas
* email: usuario@email.com
* senha: usuario

Caso utilize VSCode, você terá a opção de subir todo o ambiente de desenvolvimento utilizando o plugin 
<a href="https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers">Remote Container</a>. mas vale lembrar que é orbigatório o uso do docker.
