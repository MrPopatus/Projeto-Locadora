<?php
session_start();

$usuarioLogado = isset($_SESSION['usuario_id']);
$tipoUsuario = $usuarioLogado ? $_SESSION['usuario_tipo'] : null;


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locadora de Veículos - DriveGo</title>
    <link rel="stylesheet" href="css/estilo.css">
    <script
        src="https://kit.fontawesome.com/a076d05399.js"
        crossorigin="anonymous">
    </script>
</head>
<body>
    <header class="site-header">

        <div class="container header-inner">
            <a href="index.php" class="brand-logo">
                Drive<span>Go</span>
            </a>
            <nav class="main-nav">

                
                <?php if ($usuarioLogado): ?>
                    <?php if ($tipoUsuario === 'funcionario'): ?>
                        <a href="view/carroView.php" class="nav-link">
                            Veículos
                        </a>
                        <a href="view/marcaView.php" class="nav-link">
                            Marcas
                        </a>

                    <?php endif; ?>
                    <a href="#reservas" class="nav-link">
                       Fazer Reserva
                    </a>

                    <span class="nav-link" style="color: var(--primary); font-weight: bold;">
                        Olá,
                        <?= htmlspecialchars($_SESSION['usuario_nome']); ?>

                    </span>
,
                    <a href="controller/logoutController.php" class="btn btn-primary" style="background-color: #dc2626;">
                        Sair
                    </a>


                <?php else: ?>
                    <a href="view/loginView.php" class="btn btn-primary" >
                        Entrar
                    </a>
                    <a href="view/cadastroView.php" class="btn btn-primary" >
                        Cadastrar
                    </a>

                <?php endif; ?>

            </nav>

        </div>

    </header>
        <section class="catalog-grid">
              
        </section>
    </main>
</body>
</html>
