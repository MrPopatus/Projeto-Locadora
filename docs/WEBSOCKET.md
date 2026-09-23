# Atendimento em tempo real

O atendimento usa o Apache para páginas, login e fallback HTTP, e um processo separado do Workerman para mensagens em tempo real.

## Iniciar no Windows

1. Inicie Apache e MySQL pelo XAMPP.
2. Execute `iniciar-websocket.bat` na raiz do projeto.
3. Mantenha a janela aberta enquanto estiver testando o atendimento.

O servidor escuta em `ws://localhost:8080`.

## Comportamento

- A autenticação reutiliza o cookie da sessão PHP.
- O servidor valida no banco se o usuário pode acessar o atendimento.
- Toda mensagem é salva no MySQL antes de ser transmitida.
- Funcionários recebem avisos de novos atendimentos.
- Se o WebSocket estiver indisponível, o formulário continua usando o Controller HTTP.

## Produção

Em produção, execute o Workerman como serviço em Linux e exponha a conexão como `wss://` por meio de um proxy reverso na porta 443. A lista de origens permitidas em `websocket/servidor.php` também deve ser atualizada com o domínio real.
