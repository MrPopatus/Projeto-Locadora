use locadora;

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
