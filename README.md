# XMX Blog 🗨️

## Descrição

Esta é uma aplicação web de blog desenvolvida como parte do desafio técnico da XMX Corp. A principal finalidade do projeto é demonstrar proficiência na construção de uma arquitetura baseada em micro-serviços/consumo de APIs externas, utilizando o framework Laravel para orquestrar a visualização de conteúdo.

A aplicação consome uma API JSON externa para buscar e apresentar três conjuntos de dados distintos: Posts, Comentários e Detalhes de Usuários (Autores). O backend Laravel atua como uma camada de serviço, realizando as chamadas HTTP e transformando os dados para o frontend, garantindo uma experiência de usuário (UX) rápida e coesa.

# Arquitetura e Estrutura

O projeto segue uma arquitetura MVC (Model-View-Controller) padrão do Laravel, com ênfase na separação de responsabilidades para o consumo de dados externos:

Service Layer (Data Fetching): Os controladores utilizam classes de serviço específicas (por exemplo, PostService, UserService) que encapsulam a lógica de comunicação com a API externa.

External API Consumption: O cliente HTTP nativo do Laravel (Illuminate\Support\Facades\Http) é utilizado para fazer as requisições GET à API.

Frontend (Blade & Tailwind CSS): A apresentação é construída com templates Blade e estilizada com Tailwind CSS para um design moderno e responsivo, focado na acessibilidade e performance em diferentes dispositivos.

## Tecnologias Utilizadas

- Laravel 12.40.2
- Tailwind v4

## Instalação

### Pré-requisitos

- PHP 8.2+

### Passos

1. Clone o repositório
```sh
   git clone github.com/rhnrod/xmx-blog
   cd xmx-blog
```

2. Instale as dependências PHP
```sh
   composer install
```
3. Configure o banco de dados
```sh
   cp .env.example .env
    php artisan key:generate
```
4. Execute as migrations
```sh
    php artisan migrate
```
5. Rode o projeto
```sh
    php artisan serve
```