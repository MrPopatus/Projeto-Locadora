<?php
// Inicia a sessão no topo antes de qualquer saída HTML
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Locadora de Veículos - DriveGo</title>
  <link rel="stylesheet" href="css/estilo.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <!-- Header / Navbar -->
  <header class="site-header">
    <div class="container header-inner">
      <a href="index.php" class="brand-logo">Drive<span>Go</span></a>
      
      <nav class="main-nav">
        <a href="#reservas" class="nav-link">Fazer Reserva</a>
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
          <!-- Exibido quando o usuário ESTÁ logado -->
          <span class="nav-link" style="color: var(--primary); font-weight: bold;">
            Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>
          </span>
          <a href="controller/logoutController.php" class="btn btn-primary" style="background-color: #dc2626;">Sair</a>
        <?php else: ?>
          <!-- Exibido quando o usuário NÃO está logado -->
          <a href="view/loginView.php" class="btn btn-primary">Entrar</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="container">
    
    <!-- Widget de Busca / Filtro -->
    <section class="search-widget" id="reservas">
      <form class="search-form" action="view/catalogoView.php" method="GET">
        <div class="form-group">
          <label for="retirada">Local de Retirada</label>
          <select id="retirada" name="retirada" class="form-control">
            <option value="gru">Aeroporto Guarulhos (GRU)</option>
            <option value="sp-centro">São Paulo - Centro</option>
          </select>
        </div>

        <div class="form-group">
          <label for="data-retirada">Data Retirada</label>
          <input type="date" id="data-retirada" name="data_retirada" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="data-devolucao">Data Devolução</label>
          <input type="date" id="data-devolucao" name="data_devolucao" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Buscar Carros</button>
      </form>
    </section>

    <!-- Catálogo de Veículos -->
    <section class="catalog-grid">
      
      <!-- Card de Veículo -->
      <article class="vehicle-card">
        <div class="vehicle-image-wrapper">
          <span class="category-badge">SUV</span>
          <img src="https://via.placeholder.com/300x180" alt="Jeep Compass" class="vehicle-image">
        </div>
        <div class="vehicle-content">
          <h3 class="vehicle-title">Jeep Compass</h3>
          <p class="vehicle-subtitle">Ou similar | Grupo SUV</p>

          <div class="vehicle-specs">
            <span class="spec-item">5 Lugares</span>
            <span class="spec-item">Automático</span>
            <span class="spec-item">Ar-cond.</span>
          </div>

          <div class="vehicle-footer">
            <div class="price-box">
              <span class="price-amount">R$ 180</span>
              <span class="price-period">/ dia</span>
            </div>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
              <a href="view/reservaView.php?carro_id=1" class="btn btn-accent">Reservar</a>
            <?php else: ?>
              <a href="view/loginView.php?redirect=reserva&carro_id=1" class="btn btn-accent">Reservar</a>
            <?php endif; ?>
          </div>
        </div>
      </article>

    </section>
  </main>

</body>
</html>