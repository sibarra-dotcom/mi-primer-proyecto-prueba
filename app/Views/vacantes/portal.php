<!doctype html>
<html lang="es-MX">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Portal de Vacantes Gibanibb</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('_partials/vacantes.css') ?>">
</head>
<body>

<div class="vacantes-module">
  <!-- Header simple para portal público -->
  <header style="position:sticky;top:0;z-index:10;background:#ffffff;border-bottom:1px solid rgba(15,23,42,.12);box-shadow:0 2px 10px rgba(2,6,23,.08);">
    <div style="max-width:1400px;margin:0 auto;padding:10px 22px;display:flex;align-items:center;gap:12px;min-height:62px;">
      <img src="<?= base_url('img/logo.jpeg') ?>" alt="Logo Gibanibb" style="width:44px;height:44px;border-radius:10px;object-fit:cover;">
      <a href="<?= base_url('vacantes/mipostulacion') ?>" style="margin-left:auto;font-size:13px;color:var(--primary);font-weight:600;text-decoration:none;">Consultar seguimiento</a>
    </div>
  </header>

  <main>
    <div class="page-title">
      <h1>Portal de Vacantes Gibanibb</h1>
    </div>

    <!-- Barra de filtros -->
    <div class="portal-filtros" id="portalFiltros">
      <div class="portal-filtros-row">
        <div class="portal-filtro-busqueda">
          <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="portal-busqueda" placeholder="Buscar...">
        </div>
        <select id="portal-filtro-depto"><option value="">Departamento</option></select>
        <select id="portal-filtro-ubicacion"><option value="">Ubicación</option></select>
        <select id="portal-filtro-jornada"><option value="">Jornada</option></select>
        <div class="portal-filtros-sep"></div>
        <select id="portal-ordenar">
          <option value="reciente">Más reciente</option>
          <option value="antigua">Más antigua</option>
          <option value="az">A - Z</option>
          <option value="za">Z - A</option>
        </select>
        <div class="portal-vista-toggle">
          <button type="button" id="portal-vista-grid" class="portal-vista-btn active" title="Vista cuadrícula" onclick="setPortalVista('grid')">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
          </button>
          <button type="button" id="portal-vista-lista" class="portal-vista-btn" title="Vista lista" onclick="setPortalVista('lista')">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
          </button>
          <button type="button" id="portal-limpiar" class="portal-limpiar-btn" onclick="limpiarFiltrosPortal()" title="Limpiar filtros">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Limpiar
          </button>
        </div>
      </div>
      <div class="portal-filtros-resumen" id="portalFiltrosResumen"></div>
    </div>

    <div class="vacantes-grid" id="vacantesGrid"></div>

  </main>

  <!-- Modal: Aplicar a Vacante -->
  <div id="modal-aplicar" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Aplicar a Vacante</h3>
        <button class="close-modal" onclick="closeModal('aplicar')">
          <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-aplicar">
          <input type="hidden" id="aplicar-vacante-id">
          <div id="aplicar-vacante-banner" style="background:rgba(0,121,64,.07);border:1px solid rgba(0,121,64,.2);border-radius:10px;padding:16px 20px;margin-bottom:20px;text-align:center;">
            <p style="margin:0;font-size:12px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);font-weight:700;">Aplicando a la vacante</p>
            <p id="aplicar-vacante-nombre" style="margin:4px 0 0;font-size:20px;font-weight:900;color:var(--primary);"></p>
          </div>
          <div class="card">
            <div class="card-header"><h2>Datos Personales</h2></div>
            <div class="grid">
              <div class="col-6"><label for="apli-nombre">Nombre(s) *</label><input id="apli-nombre" type="text" required></div>
              <div class="col-6"><label for="apli-apellidos">Apellido(s) *</label><input id="apli-apellidos" type="text" required></div>
              <div class="col-6"><label for="apli-email">Correo Electrónico *</label><input id="apli-email" type="email" required></div>
              <div class="col-6">
                <label for="apli-telefono">Teléfono *</label>
                <input id="apli-telefono" type="tel" required placeholder="10 dígitos" maxlength="10" pattern="\d{10}" title="Ingresa exactamente 10 dígitos" oninput="this.value=this.value.replace(/\D/g,'')">
              </div>
              <div class="col-6">
                <label for="apli-fecha-nacimiento">Fecha de Nacimiento *</label>
                <div class="fecha-input-wrapper">
                  <input id="apli-fecha-nacimiento" type="text" required placeholder="dd/mm/aaaa" maxlength="10" oninput="mascaraFecha(this)" pattern="\d{2}/\d{2}/\d{4}" title="Formato dd/mm/aaaa">
                  <button type="button" class="fecha-cal-btn" onclick="toggleCalendario(this)">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="col-6">
                <label for="apli-ciudad">Ciudad de Residencia *</label>
                <select id="apli-ciudad" required>
                  <option value="">Seleccionar...</option>
                  <option>Guadalajara</option>
                  <option>Tonalá</option>
                  <option>Tlaquepaque</option>
                  <option>Zapopan</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h2>Experiencia Profesional</h2></div>
            <div class="grid">
              <div class="col-12">
                <label for="apli-experiencia">Años de Experiencia *</label>
                <select id="apli-experiencia" required>
                  <option value="">Seleccionar...</option>
                  <option>Sin experiencia</option>
                  <option>1-2 años</option>
                  <option>3-5 años</option>
                  <option>6-10 años</option>
                  <option>Más de 10 años</option>
                </select>
              </div>
              <div class="col-6"><label for="apli-ultima-empresa">Última Empresa</label><input id="apli-ultima-empresa" type="text" placeholder="Nombre de la empresa"></div>
              <div class="col-6"><label for="apli-ultimo-puesto">Último Puesto</label><input id="apli-ultimo-puesto" type="text" placeholder="Puesto desempeñado"></div>
              <div class="col-12">
                <label>Habilidades Principales (máximo 3)</label>
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                  <input id="apli-habilidad-input" type="text" placeholder="Escribe una habilidad y presiona Agregar" style="flex:1;">
                  <button type="button" class="btn btn-ghost btn-small" onclick="agregarHabilidad()">Agregar</button>
                </div>
                <div id="apli-habilidades-tags" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
                <input type="hidden" id="apli-habilidades">
                <p class="help"><span id="apli-habilidades-count">0</span>/3 habilidades agregadas</p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header"><h2>Educación</h2></div>
            <div class="grid">
              <div class="col-12">
                <label for="apli-escolaridad">Nivel de Escolaridad *</label>
                <select id="apli-escolaridad" required>
                  <option value="">Seleccionar...</option>
                  <option>Secundaria</option>
                  <option>Preparatoria</option>
                  <option>Licenciatura</option>
                  <option>Maestría</option>
                  <option>Doctorado</option>
                </select>
              </div>
              <div class="col-12"><label for="apli-carrera">Carrera/Especialidad</label><input id="apli-carrera" type="text"></div>
            </div>
          </div>
          <div class="card" id="apli-cv-seccion">
            <div class="card-header"><h2>Curriculum Vitae</h2></div>
            <div class="grid">
              <div class="col-12">
                <label for="apli-cv" id="apli-cv-label">Adjuntar CV</label>
                <input type="file" id="apli-cv" accept=".pdf,.doc,.docx">
                <p class="help" id="apli-cv-help">Opcional. Formatos: PDF, DOC, DOCX. Máximo 2MB.</p>
              </div>
            </div>
          </div>
          <div class="actions">
            <button type="button" class="btn btn-ghost" onclick="closeModal('aplicar')">Cancelar</button>
            <button type="submit" class="btn btn-primary">Enviar Aplicación</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal: Detalle Vacante Pública -->
  <div id="modal-detalle-vacante-publica" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Detalle de Vacante</h3>
        <button class="close-modal" onclick="closeModal('detalle-vacante-publica')">
          <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="modal-body" id="detalle-vacante-publica-content"></div>
    </div>
  </div>

  <!-- Toast -->
  <div id="toast" class="toast"></div>

  <!-- Celebration -->
  <div id="celebration" class="celebration">
    <div class="celebration-card">
      <div class="celebration-title">¡Felicidades!</div>
      <p class="celebration-sub">Has completado el 100% de los documentos.</p>
      <button class="btn btn-primary" style="margin-top:16px;" onclick="cerrarCelebracion()">Aceptar</button>
    </div>
  </div>
  <div id="confettiLayer" class="confetti-layer"></div>

  <!-- Modal: Código de Seguimiento -->
  <div id="modal-codigo-seguimiento" class="modal">
    <div class="modal-content" style="max-width:480px;text-align:center;">
      <div class="modal-header" style="justify-content:flex-end;border:none;padding:12px 16px 0;">
        <button class="close-modal" onclick="cerrarModalCodigo()">
          <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="modal-body" style="padding:0 28px 28px;">
        <p style="font-size:12px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);font-weight:700;margin:0 0 8px;">Tu código de seguimiento</p>
        <div id="codigo-seguimiento-display" style="font-family:monospace;font-size:42px;font-weight:900;color:var(--primary);letter-spacing:8px;margin:8px 0 16px;"></div>
        <p style="font-size:14px;color:var(--muted);margin:0 0 20px;line-height:1.5;">Guarda este código para dar seguimiento a tu postulación. Con él podrás consultar el estado de tu proceso en cualquier momento.</p>
        <div style="display:flex;flex-direction:column;gap:10px;align-items:center;">
          <button class="btn btn-primary" onclick="copiarCodigoSeguimiento()" style="width:100%;">Copiar Código</button>
          <a href="<?= base_url('vacantes/mipostulacion') ?>" class="btn btn-ghost" style="text-decoration:none;width:100%;display:inline-block;">Consultar Mi Postulación</a>
          <button class="btn btn-ghost btn-small" onclick="cerrarModalCodigo()" style="margin-top:4px;">Entendido</button>
        </div>
      </div>
    </div>
  </div>
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
