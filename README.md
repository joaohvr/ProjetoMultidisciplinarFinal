# Projeto Multidisciplinar Final - Loja Nova Era

---

## Como rodar o projeto

### 1. Configuração do Ambiente

- Instale e execute o **XAMPP** na sua máquina.
- Certifique-se de iniciar os serviços **Apache** e **MySQL** pelo painel do XAMPP.

---

### 2. Criação do Banco de Dados

Abra o **phpMyAdmin** ou um cliente MySQL de sua preferência e execute o script abaixo para criar o banco e as tabelas necessárias:

```sql
CREATE DATABASE loja;

USE loja;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    descricao TEXT,
    preco DECIMAL(10,2),
    imagem VARCHAR(255),
    quantidade INT(11),
    inativo TINYINT(1) DEFAULT 0
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL -- senha já hashada com password_hash()
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    numero VARCHAR(5) NOT NULL,
    CEP VARCHAR(20) NOT NULL,
    numeroTel VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    endereco_entrega VARCHAR(255),
    numero INT NOT NULL,
    metodo_pagamento VARCHAR(50),
    status VARCHAR(30) NOT NULL DEFAULT 'Em Separação',
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
```
##Configuração 
Copie todos os arquivos da pasta do projeto para o diretório do XAMPP:
 ```C:\xampp\htdocs\lojaxyz\```

##Acessando o Projeto
Acesse no navegador a URL:
http://localhost/lojaxyz/index.php
para visualizar a loja.

Para acessar o painel administrativo, acesse:
http://localhost/lojaxyz/login_admin.php

##Criando um usuário administrador de teste
Para criar um usuário admin para testes, insira um registro na tabela admins usando o comando SQL abaixo.

ATENÇÃO: A senha deve ser gerada com password_hash() para segurança. Para testes rápidos, você pode inserir uma senha simples, mas não use isso em produção.

INSERT INTO admins (username, password) VALUES ('seu_usuario', '{hash_da_senha}');

Projeto desenvolvido por João Henrique Vidal Ribeiro como trabalho final do curso multidisciplinar.
