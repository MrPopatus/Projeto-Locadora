create database locadora
default character set utf8
default collate utf8_general_ci;

use locadora;

create table marca (
    idMarca int auto_increment unique primary key,
    nomeMarca varchar(30) not null unique
);

create table veiculo (
    idVeiculo int auto_increment unique primary key,
    modelo varchar(60) not null,
    ano char(4),
    renavam char(9) not null unique,
    valorDiaria decimal(11, 2) not null,
    idMarcaV int not null,
    constraint fkMarcaV foreign key (idMarcaV) references marca (idMarca)
);

create table cliente (
    idCliente int auto_increment unique primary key,
    nomeCliente varchar(60) not null,
    telefone char(15) not null unique,
    email varchar(60) not null unique,
    senha varchar(100) not null
);

create table locacao (
    idLocacao int auto_increment primary key,
    idClienteL int not null,
    idVeiculoL int not null,
    dataLocacao date not null,
    dataDevolucaoPrevista date not null,
    dataDevolucaoReal date,
    cnh char(9) not null unique,
    cartaoNumero char(16) not null,
    cpf char(11) not null unique,
    valorPagar decimal(11, 2) not null,
    constraint fkClienteL foreign key (idClienteL) references cliente (idCliente),
    constraint fkVeiculoL foreign key (idVeiculoL) references veiculo (idVeiculo)
);

select * from cliente;

select * from marca;
    
select * from veiculo;

-- drop database locadora

alter table cliente add column tipoUsuario enum('cliente', 'funcionario')
NOT NULL DEFAULT 'cliente';

insert into cliente
(nomeCliente, telefone, email, senha, tipoUsuario)
values
('admin', '676767676767676', 'admin@gmail.com', '123', 'funcionario');