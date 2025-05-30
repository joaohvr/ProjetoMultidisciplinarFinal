# ProjetoMultidisciplinarFinal

##Como rodar?

### Instancie o XAMPP em sua maquina e crie o banco com o seguinte script

CREATE DATABASE loja;

USE loja;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    descricao TEXT,
    preco DECIMAL(10,2),
    imagem VARCHAR(255),
    quantidade INT(11),
    inativo INT(1)
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
    endereco_entrega VARCHAR(50),
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
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);


após isso, jogue os arquivos da pasta em C:\xampp\htdocs\ 

após isso, instancie o serviço do apache xampp e o mysql e entre na url
http://localhost/lojaxyz/index.php

para acessar o painel de admin use 
http://localhost/lojaxyz/login_admin.php

para fazer login de teste use
insert into admins (username, password) VALUES ('teste','teste')
substituindo o teste por seu login e sua senha


projeto desenvolvido por João Henrique Vidal Ribeiro
