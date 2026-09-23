(() => {
    const mensagens = document.getElementById('mensagens');
    const formulario = document.getElementById('formulario-atendimento');
    const formularioEncerrar = document.getElementById('formulario-encerrar');
    const campoMensagem = document.getElementById('mensagem');
    const status = document.getElementById('status-websocket');
    const listaAtendimentos = document.querySelector('.atendimentos');
    const tipoUsuario = document.body.dataset.tipoUsuario;
    const idUsuario = Number(document.body.dataset.usuarioId || 0);
    let idAtendimento = Number(document.body.dataset.atendimentoId || 0);
    let socket;
    let tentativaReconexao;
    let deveReconectar = true;

    function definirStatus(texto, conectado) {
        if (!status) return;
        const rotulo = status.querySelector('span:last-child');
        if (rotulo) {
            rotulo.textContent = texto;
        } else {
            status.textContent = texto;
        }
        status.dataset.conectado = conectado ? 'true' : 'false';
    }

    function adicionarMensagem(evento) {
        if (!mensagens || Number(evento.idAtendimento) !== idAtendimento) return;

        const vazio = mensagens.querySelector('.mensagem-vazia');
        if (vazio) vazio.remove();

        const elemento = document.createElement('article');
        elemento.classList.add('mensagem', evento.tipoRemetente);

        const balao = document.createElement('div');
        balao.classList.add('mensagem__bubble');

        const meta = document.createElement('div');
        meta.classList.add('mensagem__meta');

        const autor = document.createElement('strong');
        autor.textContent = evento.nomeRemetente;

        const horario = document.createElement('span');
        horario.textContent = new Date().toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit'
        });

        const conteudo = document.createElement('p');
        conteudo.textContent = evento.conteudo;

        meta.append(autor, horario);
        balao.append(meta, conteudo);
        elemento.appendChild(balao);
        mensagens.appendChild(elemento);
        mensagens.scrollTop = mensagens.scrollHeight;
    }

    function atualizarLista(evento) {
        if (tipoUsuario !== 'funcionario' || !listaAtendimentos) return;

        let link = listaAtendimentos.querySelector(`[data-atendimento-id="${evento.idAtendimento}"]`);
        if (!link) {
            const vazio = listaAtendimentos.querySelector('.lista-vazia');
            if (vazio) vazio.remove();
            link = document.createElement('a');
            link.href = `?id=${evento.idAtendimento}`;
            link.dataset.atendimentoId = evento.idAtendimento;
            link.classList.add('support-conversation');

            const avatar = document.createElement('span');
            avatar.classList.add('support-conversation__avatar');
            avatar.textContent = evento.nomeCliente.charAt(0).toUpperCase();

            const conteudo = document.createElement('span');
            conteudo.classList.add('support-conversation__content');

            const nome = document.createElement('strong');
            nome.textContent = evento.nomeCliente;

            const protocolo = document.createElement('small');
            protocolo.textContent = `Atendimento #${evento.idAtendimento}`;

            const situacao = document.createElement('span');
            situacao.classList.add('support-conversation__status', 'support-conversation__status--aberto');
            situacao.textContent = 'aberto';

            conteudo.append(nome, protocolo);
            link.append(avatar, conteudo, situacao);
            listaAtendimentos.prepend(link);
        }

        if (!idAtendimento) {
            window.location.href = link.href;
        }
    }

    function conectar() {
        const protocolo = window.location.protocol === 'https:' ? 'wss' : 'ws';
        socket = new WebSocket(`${protocolo}://${window.location.hostname}:8080`);
        definirStatus('Conectando…', false);

        socket.addEventListener('open', () => definirStatus('Online', true));

        socket.addEventListener('message', (event) => {
            let dados;

            try {
                dados = JSON.parse(event.data);
            } catch (erro) {
                return;
            }

            if (dados.tipo === 'erro') {
                alert(dados.mensagem);
                if (dados.mensagem === 'Sessão inválida. Entre novamente.') {
                    deveReconectar = false;
                    socket.close();
                }
                return;
            }

            if (dados.tipo === 'nova_mensagem') {
                if (!idAtendimento && tipoUsuario === 'cliente') {
                    idAtendimento = Number(dados.idAtendimento);
                    document.body.dataset.atendimentoId = String(idAtendimento);
                }
                atualizarLista(dados);
                adicionarMensagem(dados);

                if (Number(dados.idRemetente) === idUsuario && campoMensagem) {
                    campoMensagem.value = '';
                    campoMensagem.focus();
                }
            }

            if (dados.tipo === 'atendimento_encerrado' && Number(dados.idAtendimento) === idAtendimento) {
                definirStatus('Atendimento encerrado', false);
                formulario?.remove();
                formularioEncerrar?.remove();
            }
        });

        socket.addEventListener('close', () => {
            if (!deveReconectar) {
                definirStatus('Sessão encerrada', false);
                return;
            }

            definirStatus('Reconectando…', false);
            clearTimeout(tentativaReconexao);
            tentativaReconexao = setTimeout(conectar, 3000);
        });

        socket.addEventListener('error', () => definirStatus('Modo sem tempo real', false));
    }

    formulario?.addEventListener('submit', (event) => {
        if (!socket || socket.readyState !== WebSocket.OPEN) return;

        event.preventDefault();
        const conteudo = campoMensagem.value.trim();
        if (!conteudo) return;

        socket.send(JSON.stringify({
            tipo: 'enviar_mensagem',
            idAtendimento: idAtendimento || null,
            conteudo
        }));
    });

    formularioEncerrar?.addEventListener('submit', (event) => {
        if (!socket || socket.readyState !== WebSocket.OPEN) return;

        event.preventDefault();
        if (!window.confirm('Deseja encerrar este atendimento?')) return;
        socket.send(JSON.stringify({ tipo: 'encerrar_atendimento', idAtendimento }));
    });

    if (mensagens) {
        mensagens.scrollTop = mensagens.scrollHeight;
    }

    conectar();
})();
