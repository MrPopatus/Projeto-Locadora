create database locadora
default character set utf8
default collate utf8_general_ci;

use locadora;

create table veiculo(
	idVeiculo int auto_increment unique primary key,
    modelo varchar(60) not null,
    ano char(4),
    renavam char(9) not null unique,
    preco decimal(11,2) not null
);

create table marca(
	idMarca int auto_increment unique primary key,
    nomeMarca varchar(30) not null,
    idVeiculoM int not null,
    constraint fkVeiculoM foreign key (idVeiculoM) references veiculo (idVeiculo)
);

create table cliente(
	idCliente int auto_increment unique primary key,
    nomeCliente varchar(60) not null,
    telefone char(15) not null unique,
    cpf char(11) not null unique,
    email varchar(60) not null unique
);
create table compra(
	idCompra int auto_increment unique primary key,
    dataCompra date not null,
    idVeiculoC int not null,
    idClienteC int not null,
    constraint fkCliente foreign key (idClienteC) references cliente (idCliente),
    constraint fkVeiculoC foreign key (idVeiculoC) references veiculo (idVeiculo)
);
-- drop database locadora
