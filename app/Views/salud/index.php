<?php echo view('_partials/header'); ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= load_asset('_partials/salud.css') ?>">

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
<div class="relative flex flex-col font-titil">

  <?php echo view('salud/_partials/navbar'); ?>

  <div class="salud-module">

    <main>

      <!-- ==================== VISTA 1: EXPEDIENTES ==================== -->
      <div id="view-expedientes" class="view active">

        <div class="salud-sticky-header font-titil">
          <span class="text-2xl font-semibold text-title leading-none flex-1 text-center">
            <i class="fas fa-notes-medical" style="margin-right:8px;color:var(--primary);"></i>Expediente Medico
          </span>
        </div>

        <!-- Pendientes de Examen Medico -->
        <div class="card" id="card-pendientes">
          <div class="card-header">
            <h2><i class="fas fa-user-clock" style="margin-right:8px;color:var(--primary);"></i>Pendientes de Examen Medico</h2>
          </div>
          <div id="tabla-pendientes"></div>
        </div>

        <!-- Historial de Examenes -->
        <div class="card" id="card-historial">
          <div class="card-header">
            <h2><i class="fas fa-history" style="margin-right:8px;color:var(--primary);"></i>Examenes Realizados</h2>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
              <input type="text" id="buscar-historial" placeholder="Buscar por nombre..."
                     style="font-size:13px;padding:6px 10px;border:1px solid var(--border);border-radius:6px;font-family:inherit;">
              <select id="filtro-resultado" style="font-size:13px;padding:6px 10px;border:1px solid var(--border);border-radius:6px;font-family:inherit;">
                <option value="">Todos</option>
                <option value="apto">Apto</option>
                <option value="no-apto">No Apto</option>
              </select>
            </div>
          </div>
          <div id="tabla-historial"></div>
        </div>

      </div>

      <!-- ==================== VISTA 2: FORMULARIO SOC-REG-05 ==================== -->
      <div id="view-formulario" class="view">

        <div class="salud-sticky-header font-titil">
          <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <button class="btn btn-ghost" onclick="volverExpedientes()">
              <i class="fas fa-arrow-left"></i> Regresar
            </button>
            <span class="text-xl font-semibold text-title" id="form-titulo-candidato">
              SOC-REG-05 - Examen Medico de Ingreso
            </span>
            <button class="btn btn-ghost" onclick="guardarBorrador()" style="margin-left:auto;">
              <i class="fas fa-save"></i> Guardar Borrador
            </button>
          </div>
        </div>

        <form id="form-examen-medico" novalidate>
          <input type="hidden" id="examen-candidato-id">
          <div id="form-doc-container"></div>
        </form>
      </div>

      <!-- ==================== VISTA 3: DETALLE EXAMEN (solo lectura) ==================== -->
      <div id="view-detalle-examen" class="view">
        <div class="salud-sticky-header font-titil">
          <div style="display:flex;align-items:center;gap:12px;width:100%;">
            <button class="btn btn-ghost" onclick="showView('expedientes')">
              <i class="fas fa-arrow-left"></i> Regresar
            </button>
            <span class="text-xl font-semibold text-title" id="detalle-titulo">Detalle del Examen</span>
            <button class="btn btn-primary" onclick="window.print()" style="margin-left:auto;">
              <i class="fas fa-print"></i> Imprimir
            </button>
          </div>
        </div>
        <div id="detalle-examen-content"></div>
      </div>

    </main>

    <!-- Toast -->
    <div id="toast-salud" class="toast"></div>

  </div><!-- /.salud-module -->

  <script>
    window.__SALUD_CONFIG = {
      rol: <?= json_encode($saludRol) ?>,
      allRoles: <?= json_encode(!empty($allRoles)) ?>,
      rolNombres: <?= json_encode($rolNombres) ?>,
      userName: <?= json_encode($userName) ?>,
      userEmail: <?= json_encode($userEmail) ?>,
      rolNombre: <?= json_encode($rolNombre) ?>,
      logoutUrl: <?= json_encode(base_url('auth/signout')) ?>,
      vacantesUrl: <?= json_encode(base_url('vacantes')) ?>
    };
  </script>
  <script src="<?= load_asset('js/salud.js') ?>" charset="utf-8"></script>
</div>
</body>
</html>
