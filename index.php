<?php
  session_start();

  $usuarioLogado = isset($_SESSION['usuario_id']);
  $tipoUsuario = $usuarioLogado ? $_SESSION['usuario_tipo'] : null;

  require_once 'model/carroModel.php';

  $carroModel = new Carro();

  $carros = $carroModel->listarCarros();
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
                    <span class="nav-user">
                        Olá,
                        <?= htmlspecialchars($_SESSION['usuario_nome']); ?>

                    </span>
                    <a href="controller/logoutController.php" class="btn btn-danger">
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
    <main>
        <section class="hero">
            <div class="container hero__content">
                <span class="eyebrow eyebrow--light">Locação simples e segura</span>
                <h1>O carro certo para cada caminho.</h1>
                <p>Escolha seu veículo, reserve online e saia dirigindo com tranquilidade.</p>
                <a href="#veiculos" class="btn btn-accent">Ver veículos</a>
            </div>
        </section>
        <section class="container catalog-section" id="veiculos">
            <div class="section-heading">
                <div><span class="eyebrow">Nossa frota</span><h2>Veículos disponíveis</h2></div>
                <p>Modelos revisados, prontos para a sua próxima viagem.</p>
            </div>
            <div class="catalog-grid">

            <?php foreach ($carros as $carro): ?>

                <article class="vehicle-card">

                    <div class="vehicle-image-wrapper">
                        <img 
                            src="<?= htmlspecialchars($carro['imagemVeiculo']); ?>"
                            alt="<?= htmlspecialchars($carro['modelo']); ?>"
                            class="vehicle-image"
                        >

                    </div>

                    <div class="vehicle-content">

                        <h3 class="vehicle-title">
                            <?= htmlspecialchars($carro['nomeMarca']); ?>
                            <?= htmlspecialchars($carro['modelo']); ?>
                        </h3>

                        <p class="vehicle-subtitle">
                            Ano <?= htmlspecialchars($carro['ano']); ?>
                        </p>

                        <div class="vehicle-footer">
                            <div class="price-box">
                                <span class="price-amount">
                                    R$ <?= number_format($carro['valorDiaria'], 2, ',', '.'); ?>
                                </span>

                                <span class="price-period">
                                    / dia
                                </span>

                            </div>


                            <?php if ($usuarioLogado && $tipoUsuario === 'funcionario'): ?>

                                <a 
                                    href="view/editarView.php?id=<?= $carro['idVeiculo']; ?>"
                                    class="btn btn-primary"
                                >
                                    Editar
                                </a>

                            <?php else: ?>

                                <a 
                                    href="view/detalhesView.php?id=<?= $carro['idVeiculo']; ?>"
                                    class="btn btn-accent"
                                >
                                    Detalhes
                                </a>

                            <?php endif; ?>

                            
                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

            <?php if (empty($carros)): ?>
                <div class="empty-state"><h3>Nenhum veículo disponível agora</h3><p>Volte em breve para conferir a nossa frota.</p></div>
            <?php endif; ?>
            </div>

        </section>
    </main>
    <footer class="site-footer"><div class="container"><a href="index.php" class="brand-logo">Drive<span>Go</span></a><p>&copy; <?= date('Y'); ?> DriveGo. Mobilidade para o seu dia.</p></div></footer>
</body>
</html>
