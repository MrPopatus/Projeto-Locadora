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
not null default 'cliente';

alter table veiculo add column  imagemVeiculo varchar(255) not null;

alter table veiculo
add column statusVeiculo enum('ativo', 'reservado', 'inativo', 'cancelado')
not null default 'ativo';

insert into cliente
(nomeCliente, telefone, email, senha, tipoUsuario)
values
('admin', '676767676767676', 'admin@gmail.com', '123', 'funcionario');


alter table locacao
drop index cpf;

alter table locacao
drop index cnh;

select * from locacao;

alter table locacao
add column statusLocacao enum(
    'reservado',
    'ativo',
    'manutencao',
    'inativo',
    'cancelado'
) not null default 'reservado';

create table manutencao (
    idManutencao int auto_increment primary key ,
    idVeiculoM int not null,
    tipo varchar(50) not null,
    descricao text,
    dataInicio date not null,
    dataFim date not null,
    custo decimal(10,2) default 0,
    status enum('agendada', 'em_andamento', 'finalizada', 'cancelada')
        default 'agendada',

    foreign key (idVeiculoM)
        references veiculo(idVeiculo)
);

create table atendimento (
    idAtendimento int auto_increment primary key,
    idClienteA int not null,
    idFuncionarioA int null,
    statusAtendimento enum('aberto', 'encerrado') not null default 'aberto',
    dataAbertura datetime not null default current_timestamp,
    dataEncerramento datetime null,
    constraint fkAtendimentoCliente foreign key (idClienteA) references cliente(idCliente),
    constraint fkAtendimentoFuncionario foreign key (idFuncionarioA) references cliente(idCliente)
);

create table mensagem (
    idMensagem int auto_increment primary key,
    idAtendimentoM int not null,
    idRemetente int not null,
    tipoRemetente enum('cliente', 'funcionario') not null,
    conteudo text not null,
    dataEnvio datetime not null default current_timestamp,
    constraint fkMensagemAtendimento foreign key (idAtendimentoM) references atendimento(idAtendimento),
    constraint fkMensagemRemetente foreign key (idRemetente) references cliente(idCliente)
);

alter table cliente
modify column senha varchar(255) not null;


