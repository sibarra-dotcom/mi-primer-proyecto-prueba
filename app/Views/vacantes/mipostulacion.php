<!doctype html>
<html lang="es-MX">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Consulta Tu Postulación</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('_partials/vacantes.css') ?>">
</head>
<body>

<div class="vacantes-module">
  <!-- Header simple -->
  <header style="position:sticky;top:0;z-index:10;background:#ffffff;border-bottom:1px solid rgba(15,23,42,.12);box-shadow:0 2px 10px rgba(2,6,23,.08);">
    <div style="max-width:1400px;margin:0 auto;padding:10px 22px;display:flex;align-items:center;gap:12px;min-height:62px;">
      <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo Gibanibb" style="width:44px;height:44px;border-radius:10px;object-fit:cover;">
    </div>
  </header>

  <main>
    <div class="page-title">
      <h1>Consulta Tu Postulación</h1>
    </div>

    <!-- Buscar por código -->
    <div id="seguimiento-buscar" style="max-width:480px;margin:0 auto;">
      <div style="background:#ffffff;border:1px solid rgba(15,23,42,.12);border-radius:12px;padding:32px 28px;text-align:center;">
        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;opacity:.7;">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <p style="font-size:15px;color:var(--text);font-weight:600;margin:0 0 4px;">Ingresa tu código de seguimiento</p>
        <p style="font-size:13px;color:var(--muted);margin:0 0 20px;">El código de 6 dígitos que recibiste al aplicar a una vacante.</p>
        <input
          id="input-codigo"
          type="text"
          inputmode="numeric"
          maxlength="6"
          pattern="\d{6}"
          placeholder="000000"
          class="input-codigo-seguimiento"
          oninput="this.value=this.value.replace(/\D/g,'')"
          onkeydown="if(event.key==='Enter'){event.preventDefault();consultarPostulacion();}"
          style="text-align:center;font-family:monospace;font-size:32px;font-weight:800;letter-spacing:8px;padding:14px 16px;max-width:280px;margin:0 auto 16px;display:block;"
        >
        <button class="btn btn-primary" onclick="consultarPostulacion()" style="width:100%;max-width:280px;">Consultar</button>
        <div style="margin-top:16px;">
          <a href="<?= base_url('vacantes/portal') ?>" style="font-size:13px;color:var(--primary);font-weight:600;text-decoration:none;">&larr; Volver a vacantes</a>
        </div>
      </div>
    </div>

    <!-- Resultado -->
    <div id="seguimiento-resultado" style="display:none;"></div>
  </main>

  <!-- Toast -->
  <div id="toast" class="toast"></div>
</div><!-- /.vacantes-module -->

<script>
  window.__VACANTES_CONFIG = {
    rol: 'public',
    portalUrl: <?= json_encode(base_url('vacantes/portal')) ?>,
    mipostulacionUrl: <?= json_encode(base_url('vacantes/mipostulacion')) ?>,
    logoutUrl: <?= json_encode(base_url('/')) ?>
  };
</script>
<script src="<?= base_url('js/vacantes.js') ?>"></script>
</body>
</html>
