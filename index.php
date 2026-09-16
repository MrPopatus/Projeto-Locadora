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

                <a href="#reservas" class="nav-link">
                    Fazer Reserva
                </a>
                <?php if ($usuarioLogado): ?>
                    <?php if ($tipoUsuario === 'funcionario'): ?>
                        <a href="view/funcionario/veiculosView.php" class="nav-link">
                            Veículos
                        </a>
                        <a href="view/funcionario/marcasView.php" class="nav-link">
                            Marcas
                        </a>

                    <?php endif; ?>

                    <span class="nav-link" style="color: var(--primary); font-weight: bold;">
                        Olá,
                        <?= htmlspecialchars($_SESSION['usuario_nome']); ?>

                    </span>
                    <a href="controller/logoutController.php" class="btn btn-primary" style="background-color: #dc2626;">
                        Sair
                    </a>


                <?php else: ?>
                    <a href="view/loginView.php" class="btn btn-primary" >
                        Entrar
                    </a>

                <?php endif; ?>

            </nav>

        </div>

    </header>


        <section class="catalog-grid">
            <article class="vehicle-card">

                <div class="vehicle-image-wrapper">
                    <span class="category-badge">
                        
                    </span>

                    <img
                        src="https://via.placeholder.com/300x180"
                        alt=""
                        class="vehicle-image"
                    >

                </div>
                <div class="vehicle-content">

                    <h3 class="vehicle-title">
                        Nome
                    </h3>

                    <p class="vehicle-subtitle">
                        
                    </p>

                    <div class="vehicle-specs">
                        <span class="spec-item">
                            lugares
                        </span>

                        <span class="spec-item">
                            Automático/manual
                        </span>

                        <span class="spec-item">
                            Ar-cond.
                        </span>

                    </div>
                    <div class="vehicle-footer">
                        <div class="price-box">

                            <span class="price-amount">
                                R$ 67.00
                            </span>

                            <span class="price-period">
                                / dia
                            </span>

                        </div>
                        <?php if ($usuarioLogado): ?>
                            <a
                                href="view/reservaView.php?carro_id=1"
                                class="btn btn-accent"
                            >
                                Reservar
                            </a>

                        <?php else: ?>

                            <a
                                href="view/loginView.php?redirect=reserva&carro_id=1"
                                class="btn btn-accent"
                            >
                                Reservar
                            </a>

                        <?php endif; ?>
                    </div>
                </div>
            </article>
        </section>
    </main>

</body>
</html>
