<?php echo view('_partials/header'); ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('_partials/vacantes.css') ?>">

  <title><?= esc($title) ?></title>
</head>

<body class="relative min-h-screen">
<div class="relative flex flex-col font-titil">

  <?php echo view('dashboard/_partials/navbar'); ?>

  <div class="vacantes-module">

    <main>
      <!-- Panel RH -->
      <div id="view-gestion-dashboard" class="view active">
        <div class="page-title">
          <h1>Panel de Reclutamiento</h1>
        </div>

        <div class="tabs">
          <button class="tab active tab-rh" onclick="showView('gestion-dashboard')">Panel RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-vacantes')">Vacantes RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-candidatos')">Candidatos</button>
          <button class="tab tab-jefe" onclick="showView('solicitudes-jefe')" style="display:none;">Mis Solicitudes</button>
          <button class="tab tab-aprobacion" onclick="showView('solicitudes-aprobacion')" style="display:none;">Aprobar Solicitudes</button>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Filtrar por Fecha</h2>
          </div>
          <div class="grid">
            <div class="col-4">
              <label for="dash-fecha-inicio">Fecha Inicio</label>
              <div class="fecha-input-wrapper">
                <input id="dash-fecha-inicio" type="text" placeholder="dd/mm/aaaa" maxlength="10" oninput="mascaraFecha(this)">
                <button type="button" class="fecha-cal-btn" onclick="toggleCalendario(this)">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                  </svg>
                </button>
              </div>
            </div>
            <div class="col-4">
              <label for="dash-fecha-fin">Fecha Fin</label>
              <div class="fecha-input-wrapper">
                <input id="dash-fecha-fin" type="text" placeholder="dd/mm/aaaa" maxlength="10" oninput="mascaraFecha(this)">
                <button type="button" class="fecha-cal-btn" onclick="toggleCalendario(this)">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                  </svg>
                </button>
              </div>
            </div>
            <div class="col-4" style="display:flex;align-items:flex-end;">
              <button class="btn btn-primary" onclick="filtrarDashboard()" style="width:100%;">Aplicar Filtros</button>
            </div>
          </div>
        </div>

        <!-- Métricas Generales -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;margin-bottom:24px;">
          <div class="vacante-card">
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">Total Vacantes Activas</div>
            <div style="font-size:36px;font-weight:900;color:var(--primary);" id="dash-total-vacantes">0</div>
          </div>
          <div class="vacante-card">
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">Total Candidatos</div>
            <div style="font-size:36px;font-weight:900;color:var(--primary);" id="dash-total-candidatos">0</div>
          </div>
          <div class="vacante-card">
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">En Proceso</div>
            <div style="font-size:36px;font-weight:900;color:#f59e0b;" id="dash-en-proceso">0</div>
          </div>
          <div class="vacante-card">
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">Contratados</div>
            <div style="font-size:36px;font-weight:900;color:#10b981;" id="dash-contratados">0</div>
          </div>
        </div>

        <!-- Vista por Vacante -->
        <div class="card">
          <div class="card-header">
            <h2>Desempeño por Vacante</h2>
          </div>
          <div id="dashboard-por-vacante"></div>
        </div>
      </div>

      <!-- Gestión de Vacantes RH -->
      <div id="view-gestion-vacantes" class="view">
        <div class="page-title">
          <h1>Gestión de Vacantes</h1>
        </div>

        <div class="tabs">
          <button class="tab tab-rh" onclick="showView('gestion-dashboard')">Panel RH</button>
          <button class="tab active tab-rh" onclick="showView('gestion-vacantes')">Vacantes RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-candidatos')">Candidatos</button>
          <button class="tab tab-jefe" onclick="showView('solicitudes-jefe')" style="display:none;">Mis Solicitudes</button>
          <button class="tab tab-aprobacion" onclick="showView('solicitudes-aprobacion')" style="display:none;">Aprobar Solicitudes</button>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Administrar Vacantes</h2>
          </div>
          <button class="btn btn-primary" onclick="openModal('nueva-vacante')">
            + Crear Nueva Vacante
          </button>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Buscar y Filtrar</h2>
          </div>
          <div class="grid">
            <div class="col-6">
              <label for="vac-search">Buscar por título</label>
              <input id="vac-search" type="text" placeholder="Escribe para buscar..." oninput="filtrarVacantes()">
            </div>
            <div class="col-3">
              <label for="vac-filter-departamento">Departamento</label>
              <select id="vac-filter-departamento" onchange="filtrarVacantes()">
                <option value="">Todos</option>
                <option>Administración</option>
                <option>Ventas</option>
                <option>Marketing</option>
                <option>IT</option>
                <option>RRHH</option>
                <option>Operaciones</option>
                <option>Finanzas</option>
              </select>
            </div>
            <div class="col-3">
              <label for="vac-filter-estado">Estado</label>
              <select id="vac-filter-estado" onchange="filtrarVacantes()">
                <option value="">Todas</option>
                <option value="abierta">Abiertas</option>
                <option value="cerrada">Cerradas</option>
              </select>
            </div>
          </div>
        </div>

        <div id="seccion-preaprobadas" style="display:none;">
          <div class="card">
            <div class="card-header">
              <h2>Vacantes Aprobadas</h2>
            </div>
            <div id="preaprobadasGrid" class="vacantes-grid"></div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Listado de Vacantes</h2>
          </div>
          <div id="vacantesGestionGrid" class="vacantes-grid"></div>
        </div>
      </div>

      <!-- Gestión de Candidatos -->
      <div id="view-gestion-candidatos" class="view">
        <div class="page-title">
          <h1>Gestión de Candidatos</h1>
        </div>

        <div class="tabs">
          <button class="tab tab-rh" onclick="showView('gestion-dashboard')">Panel RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-vacantes')">Vacantes RH</button>
          <button class="tab active tab-rh" onclick="showView('gestion-candidatos')">Candidatos</button>
          <button class="tab tab-jefe" onclick="showView('solicitudes-jefe')" style="display:none;">Mis Solicitudes</button>
          <button class="tab tab-aprobacion" onclick="showView('solicitudes-aprobacion')" style="display:none;">Aprobar Solicitudes</button>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Buscar y Filtrar Candidatos</h2>
          </div>
          <div class="grid">
            <div class="col-4">
              <label for="cand-search">Buscar por nombre</label>
              <input id="cand-search" type="text" placeholder="Nombre del candidato..." oninput="filtrarCandidatos()">
            </div>
            <div class="col-4">
              <label for="cand-filter-vacante">Vacante</label>
              <select id="cand-filter-vacante" onchange="filtrarCandidatos()">
                <option value="">Todas las vacantes</option>
              </select>
            </div>
            <div class="col-4">
              <label for="cand-filter-etapa">Etapa</label>
              <select id="cand-filter-etapa" onchange="filtrarCandidatos()">
                <option value="">Todas las etapas</option>
                <option value="aplicado">Aplicado</option>
                <option value="entrevista-rh">Entrevista RH</option>
                <option value="primer-filtro">Primer Filtro</option>
                <option value="entrevista-jefe">Entrevista Jefe</option>
                <option value="revision-medica">Revisión Médica</option>
                <option value="psicometrico">Psicométrico</option>
                <option value="referencias">Referencias</option>
                <option value="documentos">Documentos</option>
                <option value="contratado">Contratado</option>
                <option value="rechazado">Rechazado</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Todos los Candidatos</h2>
          </div>
          <div class="table-container" id="candidatosTable"></div>
        </div>
      </div>

      <!-- Vista: Mis Solicitudes (Jefe de Área) -->
      <div id="view-solicitudes-jefe" class="view">
        <div class="page-title">
          <h1>Mis Solicitudes de Vacante</h1>
        </div>

        <div class="tabs">
          <button class="tab tab-rh" onclick="showView('gestion-dashboard')" style="display:none;">Panel RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-vacantes')" style="display:none;">Vacantes RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-candidatos')" style="display:none;">Candidatos</button>
          <button class="tab active tab-jefe" onclick="showView('solicitudes-jefe')">Mis Solicitudes</button>
          <button class="tab tab-aprobacion" onclick="showView('solicitudes-aprobacion')" style="display:none;">Aprobar Solicitudes</button>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Solicitudes Enviadas</h2>
          </div>
          <button class="btn btn-primary" onclick="abrirNuevaSolicitud()">
            + Crear Solicitud de Vacante
          </button>
        </div>

        <div id="solicitudesJefeGrid" class="vacantes-grid"></div>
      </div>

      <!-- Vista: Aprobar Solicitudes (Gerentes) -->
      <div id="view-solicitudes-aprobacion" class="view">
        <div class="page-title">
          <h1>Aprobar Solicitudes de Vacante</h1>
        </div>

        <div class="tabs">
          <button class="tab tab-rh" onclick="showView('gestion-dashboard')" style="display:none;">Panel RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-vacantes')" style="display:none;">Vacantes RH</button>
          <button class="tab tab-rh" onclick="showView('gestion-candidatos')" style="display:none;">Candidatos</button>
          <button class="tab tab-jefe" onclick="showView('solicitudes-jefe')" style="display:none;">Mis Solicitudes</button>
          <button class="tab active tab-aprobacion" onclick="showView('solicitudes-aprobacion')">Aprobar Solicitudes</button>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Pendientes de Revisión</h2>
          </div>
          <div id="solicitudesAprobacionPendientes" class="vacantes-grid"></div>
        </div>

        <div class="card">
          <div class="card-header">
            <h2>Ya Revisadas</h2>
          </div>
          <div id="solicitudesAprobacionRevisadas" class="vacantes-grid"></div>
        </div>
      </div>
    </main>

    <!-- Info de usuario y rol -->
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:8px 22px 0;">
      <div class="user-info">
        <span id="user-name" class="user-name"><?= esc($userName) ?></span>
        <span id="user-role" class="user-role-badge"><?= esc($rolNombre) ?></span>
        <?php if (!empty($allRoles)): ?>
        <select id="select-rol-doble" class="rol-selector-mini" onchange="cambiarRol(this.value)">
          <?php foreach ($rolNombres as $rolKey => $rolLabel): ?>
          <option value="<?= esc($rolKey) ?>" <?= $rolKey === $vacantesRol ? 'selected' : '' ?>><?= esc($rolLabel) ?></option>
          <?php endforeach; ?>
        </select>
        <?php elseif ($dobleRol): ?>
        <select id="select-rol-doble" class="rol-selector-mini" onchange="cambiarRol(this.value)">
          <option value="jefe-finanzas">Jefe - Finanzas</option>
          <option value="gerente-finanzas">Gerente de Finanzas</option>
        </select>
        <?php endif; ?>
      </div>
      <a href="<?= base_url('vacantes/portal') ?>" class="btn btn-ghost btn-small" style="text-decoration:none;font-size:12px;" title="Portal Público">Ver Portal Público</a>
    </div>

    <!-- Modal: Nueva Vacante -->
    <div id="modal-nueva-vacante" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Crear Nueva Vacante</h3>
          <button class="close-modal" onclick="closeModal('nueva-vacante')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <form id="form-nueva-vacante">
            <div class="grid">
              <div class="col-12">
                <label for="vac-titulo">Título del Puesto *</label>
                <input id="vac-titulo" type="text" required placeholder="Ej. Gerente de Ventas">
              </div>
              <div class="col-6">
                <label for="vac-departamento">Departamento *</label>
                <select id="vac-departamento" required>
                  <option value="">Seleccionar...</option>
                  <option>Administración</option>
                  <option>Ventas</option>
                  <option>Marketing</option>
                  <option>IT</option>
                  <option>RRHH</option>
                  <option>Operaciones</option>
                </select>
              </div>
              <div class="col-6">
                <label for="vac-ubicacion">Ubicación *</label>
                <input id="vac-ubicacion" type="text" required placeholder="Ciudad, Estado">
              </div>
              <div class="col-6">
                <label for="vac-tipo">Tipo de Contrato *</label>
                <select id="vac-tipo" required>
                  <option value="">Seleccionar...</option>
                  <option>Tiempo Completo</option>
                  <option>Medio Tiempo</option>
                  <option>Temporal</option>
                  <option>Por Proyecto</option>
                </select>
              </div>
              <div class="col-6">
                <label for="vac-salario">Rango Salarial</label>
                <input id="vac-salario" type="text" placeholder="Ej. $15,000 - $20,000">
              </div>
              <div class="col-12">
                <label for="vac-descripcion">Descripción del Puesto *</label>
                <textarea id="vac-descripcion" required placeholder="Describe las responsabilidades y funciones principales..."></textarea>
              </div>
              <div class="col-12">
                <label for="vac-requisitos">Requisitos</label>
                <textarea id="vac-requisitos" placeholder="Experiencia requerida, habilidades, certificaciones..."></textarea>
              </div>
            </div>
            <div class="actions">
              <button type="button" class="btn btn-ghost" onclick="closeModal('nueva-vacante')">Cancelar</button>
              <button type="submit" class="btn btn-primary">Publicar Vacante</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal: Detalle de Candidato -->
    <div id="modal-detalle-candidato" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Detalle del Candidato</h3>
          <button class="close-modal" onclick="closeModal('detalle-candidato')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body" id="detalle-candidato-content"></div>
      </div>
    </div>

    <!-- Modal: Rechazar Candidato -->
    <div id="modal-rechazar-candidato" class="modal">
      <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
          <h3 class="modal-title">Rechazar Candidato</h3>
          <button class="close-modal" onclick="closeModal('rechazar-candidato')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="rechazo-candidato-id">
          <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;margin-bottom:16px;">
            <p style="margin:0;font-size:12px;text-transform:uppercase;letter-spacing:.6px;color:#991b1b;font-weight:700;">Rechazando en etapa</p>
            <p id="rechazo-etapa-info" style="margin:4px 0 0;font-size:18px;font-weight:900;color:#dc2626;"></p>
          </div>
          <label for="rechazo-motivo">Motivo del Rechazo *</label>
          <textarea id="rechazo-motivo" required placeholder="Describe el motivo por el cual se rechaza al candidato..." style="min-height:120px;"></textarea>
          <div class="actions" style="margin-top:16px;">
            <button class="btn btn-ghost" onclick="closeModal('rechazar-candidato')">Cancelar</button>
            <button class="btn btn-danger" onclick="confirmarRechazo()">Confirmar Rechazo</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Detalle de Vacante -->
    <div id="modal-detalle-vacante" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Detalle de la Vacante</h3>
          <button class="close-modal" onclick="closeModal('detalle-vacante')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body" id="detalle-vacante-content"></div>
      </div>
    </div>

    <!-- Modal: Agendar Entrevista -->
    <div id="modal-agendar-entrevista" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Agendar Entrevista</h3>
          <button class="close-modal" onclick="closeModal('agendar-entrevista')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <form id="form-agendar-entrevista">
            <input type="hidden" id="entrevista-candidato-id">
            <input type="hidden" id="entrevista-tipo">
            <div class="grid">
              <div class="col-12">
                <label for="entrevista-fecha">Fecha *</label>
                <div class="fecha-input-wrapper">
                  <input id="entrevista-fecha" type="text" required placeholder="dd/mm/aaaa" maxlength="10" oninput="mascaraFecha(this)" pattern="\d{2}/\d{2}/\d{4}" title="Formato dd/mm/aaaa">
                  <button type="button" class="fecha-cal-btn" onclick="toggleCalendario(this)">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="col-6">
                <label for="entrevista-hora">Hora *</label>
                <input id="entrevista-hora" type="time" required>
              </div>
              <div class="col-6">
                <label for="entrevista-duracion">Duración (min)</label>
                <input id="entrevista-duracion" type="number" value="60" required>
              </div>
              <div class="col-12">
                <label for="entrevista-entrevistador">Entrevistador *</label>
                <input id="entrevista-entrevistador" type="text" required placeholder="Nombre del entrevistador">
              </div>
              <div class="col-12">
                <label for="entrevista-lugar">Lugar/Link *</label>
                <input id="entrevista-lugar" type="text" required placeholder="Oficina, Sala o enlace de videollamada">
              </div>
              <div class="col-12">
                <label for="entrevista-notas">Notas</label>
                <textarea id="entrevista-notas" placeholder="Información adicional para la entrevista..."></textarea>
              </div>
            </div>
            <div class="actions">
              <button type="button" class="btn btn-ghost" onclick="closeModal('agendar-entrevista')">Cancelar</button>
              <button type="submit" class="btn btn-primary">Agendar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal: Alta de Empleado -->
    <div id="modal-alta-empleado" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Alta de Empleado</h3>
          <button class="close-modal" onclick="closeModal('alta-empleado')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <form id="form-alta-empleado">
            <input type="hidden" id="alta-candidato-id">
            <div class="card">
              <div class="card-header"><h2>Información Laboral</h2></div>
              <div class="grid">
                <div class="col-6">
                  <label for="alta-puesto">Puesto *</label>
                  <input id="alta-puesto" type="text" required readonly>
                </div>
                <div class="col-6">
                  <label for="alta-jefe">Jefe Directo *</label>
                  <input id="alta-jefe" type="text" required>
                </div>
                <div class="col-6">
                  <label for="alta-fecha-ingreso">Fecha de Ingreso *</label>
                  <div class="fecha-input-wrapper">
                    <input id="alta-fecha-ingreso" type="text" required placeholder="dd/mm/aaaa" maxlength="10" oninput="mascaraFecha(this)" pattern="\d{2}/\d{2}/\d{4}" title="Formato dd/mm/aaaa">
                    <button type="button" class="fecha-cal-btn" onclick="toggleCalendario(this)">
                      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                      </svg>
                    </button>
                  </div>
                </div>
                <div class="col-6">
                  <label for="alta-salario">Salario Mensual *</label>
                  <input id="alta-salario" type="number" required placeholder="$">
                </div>
                <div class="col-6">
                  <label for="alta-hora-entrada">Hora de Entrada *</label>
                  <select id="alta-hora-entrada" required>
                    <option value="">Seleccionar...</option>
                    <option>08:00 AM</option>
                    <option>09:00 AM</option>
                    <option>10:00 AM</option>
                  </select>
                </div>
                <div class="col-6">
                  <label for="alta-hora-salida">Hora de Salida *</label>
                  <select id="alta-hora-salida" required>
                    <option value="">Seleccionar...</option>
                    <option>05:00 PM</option>
                    <option>06:00 PM</option>
                    <option>07:00 PM</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><h2>Documentación Requerida</h2></div>
              <div class="checklist">
                <div class="check-item"><div class="check-left"><input id="doc-ine" type="checkbox"><label for="doc-ine" style="margin:0;color:var(--text);font-size:14px">INE</label></div></div>
                <div class="check-item"><div class="check-left"><input id="doc-nss" type="checkbox"><label for="doc-nss" style="margin:0;color:var(--text);font-size:14px">Número de Seguro Social (NSS)</label></div></div>
                <div class="check-item"><div class="check-left"><input id="doc-curp" type="checkbox"><label for="doc-curp" style="margin:0;color:var(--text);font-size:14px">CURP</label></div></div>
                <div class="check-item"><div class="check-left"><input id="doc-rfc" type="checkbox"><label for="doc-rfc" style="margin:0;color:var(--text);font-size:14px">RFC</label></div></div>
                <div class="check-item"><div class="check-left"><input id="doc-comprobante" type="checkbox"><label for="doc-comprobante" style="margin:0;color:var(--text);font-size:14px">Comprobante de Domicilio</label></div></div>
                <div class="check-item"><div class="check-left"><input id="doc-estudios" type="checkbox"><label for="doc-estudios" style="margin:0;color:var(--text);font-size:14px">Comprobante de Estudios</label></div></div>
              </div>
              <div class="progress-wrap">
                <div id="alta-progress-number" class="progress-number">0%</div>
                <div class="progress-bar"><div id="alta-progress-fill" class="progress-fill"></div></div>
                <div class="progress-meta">
                  <span id="alta-progress-delivered">0/6 entregados</span>
                  <span id="alta-progress-missing">6 faltantes</span>
                </div>
              </div>
            </div>
            <div class="actions">
              <button type="button" class="btn btn-ghost" onclick="closeModal('alta-empleado')">Cancelar</button>
              <button type="submit" class="btn btn-primary">Finalizar Alta</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal: Nueva Solicitud de Vacante -->
    <div id="modal-nueva-solicitud" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Nueva Solicitud de Vacante</h3>
          <button class="close-modal" onclick="closeModal('nueva-solicitud')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <!-- Paso 1: Elegir tipo -->
          <div id="sol-paso-1" class="sol-paso">
            <p style="text-align:center;font-size:16px;font-weight:700;margin-bottom:20px;color:var(--text);">¿Qué tipo de vacante necesitas?</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <div class="sol-tipo-card" onclick="seleccionarTipoSolicitud('existente')">
                <div style="font-size:32px;margin-bottom:8px;">&#128196;</div>
                <h4>Puesto Existente</h4>
                <p>Selecciona un puesto que ya existe en tu departamento</p>
              </div>
              <div class="sol-tipo-card" onclick="seleccionarTipoSolicitud('nuevo')">
                <div style="font-size:32px;margin-bottom:8px;">&#10024;</div>
                <h4>Puesto Nuevo</h4>
                <p>Solicita la creación de un puesto que no existe aún</p>
              </div>
            </div>
          </div>

          <!-- Paso 2A: Puesto existente -->
          <div id="sol-paso-2a" class="sol-paso" style="display:none;">
            <button type="button" class="btn btn-ghost btn-small" onclick="mostrarPasoSolicitud('1')" style="margin-bottom:16px;">&larr; Volver</button>
            <form id="form-solicitud-existente">
              <div class="grid">
                <div class="col-8">
                  <label for="sol-puesto-existente">Puesto del departamento</label>
                  <select id="sol-puesto-existente" required onchange="previsualizarPuesto()">
                    <option value="">Seleccionar puesto...</option>
                  </select>
                </div>
                <div class="col-4">
                  <label for="sol-cantidad">¿Cuántas vacantes?</label>
                  <input id="sol-cantidad" type="number" min="1" value="1" required>
                </div>
              </div>
              <div id="sol-preview" style="display:none;margin-top:20px;">
                <div class="card">
                  <div class="card-header"><h2>Datos del Puesto</h2></div>
                  <div class="grid">
                    <div class="col-6"><label for="sol-ex-titulo">Título</label><input id="sol-ex-titulo" type="text" readonly></div>
                    <div class="col-6"><label for="sol-ex-departamento">Departamento</label><input id="sol-ex-departamento" type="text" readonly></div>
                    <div class="col-6"><label for="sol-ex-sueldo-desde">Sueldo Desde *</label><input id="sol-ex-sueldo-desde" type="number" min="0" required placeholder="$0"></div>
                    <div class="col-6"><label for="sol-ex-sueldo-hasta">Sueldo Hasta *</label><input id="sol-ex-sueldo-hasta" type="number" min="0" required placeholder="$0"></div>
                    <div class="col-6"><label for="sol-ex-jornada">Jornada *</label><select id="sol-ex-jornada" required><option value="">Seleccionar...</option><option>Tiempo completo</option><option>Medio tiempo</option></select></div>
                    <div class="col-6"><label for="sol-ex-duracion">Duración *</label><select id="sol-ex-duracion" required><option value="">Seleccionar...</option><option>Por tiempo indeterminado</option><option>Por proyecto</option></select></div>
                    <div class="col-6"><label for="sol-ex-turno">Turno (Días) *</label><select id="sol-ex-turno" required onchange="generarTablaHorario('ex')"><option value="">Seleccionar...</option><option value="L-V">Lunes - Viernes</option><option value="L-S">Lunes - Sábado</option></select></div>
                    <div class="col-12" id="sol-ex-horario-wrapper" style="display:none;"></div>
                    <div class="col-6"><label for="sol-ex-ubicacion">Ubicación *</label><select id="sol-ex-ubicacion" required onchange="llenarDireccion('ex')"><option value="">Seleccionar...</option><option value="planta1">Planta 1 Tonalá</option><option value="planta2">Planta 2 Artes</option><option value="corporativo">Corporativo Vallarta</option><option value="cedis">CEDIS Tonalá</option></select></div>
                    <div class="col-6"><label for="sol-ex-direccion">Dirección</label><input id="sol-ex-direccion" type="text" readonly></div>
                  </div>
                </div>
                <div class="card" style="margin-top:16px;">
                  <div class="card-header"><h2>Descripción Completa del Empleo</h2></div>
                  <div class="grid">
                    <div class="col-12"><label for="sol-ex-descripcion">Descripción del Puesto</label><textarea id="sol-ex-descripcion"></textarea></div>
                    <div class="col-12"><label for="sol-ex-actividades">Actividades *</label><textarea id="sol-ex-actividades" required placeholder="Lista de actividades principales del puesto..."></textarea></div>
                    <div class="col-12"><label for="sol-ex-requisitos">Requisitos</label><textarea id="sol-ex-requisitos"></textarea></div>
                    <div class="col-12"><label for="sol-ex-conocimientos">Conocimientos</label><textarea id="sol-ex-conocimientos" placeholder="Conocimientos técnicos requeridos..."></textarea></div>
                  </div>
                </div>
                <div class="card">
                  <div class="grid">
                    <div class="col-12"><label for="sol-ex-justificacion">Justificación *</label><textarea id="sol-ex-justificacion" required placeholder="Explica por qué se necesita esta vacante..."></textarea></div>
                  </div>
                </div>
                <div class="actions">
                  <button type="button" class="btn btn-ghost" onclick="closeModal('nueva-solicitud')">Cancelar</button>
                  <button type="submit" class="btn btn-primary">Solicitar Aprobación</button>
                </div>
              </div>
            </form>
          </div>

          <!-- Paso 2B: Puesto nuevo -->
          <div id="sol-paso-2b" class="sol-paso" style="display:none;">
            <button type="button" class="btn btn-ghost btn-small" onclick="mostrarPasoSolicitud('1')" style="margin-bottom:16px;">&larr; Volver</button>
            <form id="form-solicitud-nuevo">
              <div class="grid">
                <div class="col-12"><label for="sol-nuevo-titulo">Título del Puesto *</label><input id="sol-nuevo-titulo" type="text" required placeholder="Ej. Analista de Datos Senior"></div>
                <div class="col-6"><label for="sol-nuevo-departamento">Departamento</label><input id="sol-nuevo-departamento" type="text" readonly></div>
                <div class="col-6"><label for="sol-nuevo-sueldo-desde">Sueldo Desde *</label><input id="sol-nuevo-sueldo-desde" type="number" min="0" required placeholder="$0"></div>
                <div class="col-6"><label for="sol-nuevo-sueldo-hasta">Sueldo Hasta *</label><input id="sol-nuevo-sueldo-hasta" type="number" min="0" required placeholder="$0"></div>
                <div class="col-6"><label for="sol-nuevo-jornada">Jornada *</label><select id="sol-nuevo-jornada" required><option value="">Seleccionar...</option><option>Tiempo completo</option><option>Medio tiempo</option></select></div>
                <div class="col-6"><label for="sol-nuevo-duracion">Duración *</label><select id="sol-nuevo-duracion" required><option value="">Seleccionar...</option><option>Por tiempo indeterminado</option><option>Por proyecto</option></select></div>
                <div class="col-6"><label for="sol-nuevo-turno">Turno (Días) *</label><select id="sol-nuevo-turno" required onchange="generarTablaHorario('nuevo')"><option value="">Seleccionar...</option><option value="L-V">Lunes - Viernes</option><option value="L-S">Lunes - Sábado</option></select></div>
                <div class="col-12" id="sol-nuevo-horario-wrapper" style="display:none;"></div>
                <div class="col-6"><label for="sol-nuevo-ubicacion">Ubicación *</label><select id="sol-nuevo-ubicacion" required onchange="llenarDireccion('nuevo')"><option value="">Seleccionar...</option><option value="planta1">Planta 1 Tonalá</option><option value="planta2">Planta 2 Artes</option><option value="corporativo">Corporativo Vallarta</option><option value="cedis">CEDIS Tonalá</option></select></div>
                <div class="col-6"><label for="sol-nuevo-direccion">Dirección</label><input id="sol-nuevo-direccion" type="text" readonly></div>
              </div>
              <div class="card" style="margin-top:16px;">
                <div class="card-header"><h2>Descripción Completa del Empleo</h2></div>
                <div class="grid">
                  <div class="col-12"><label for="sol-nuevo-descripcion">Descripción del Puesto *</label><textarea id="sol-nuevo-descripcion" required placeholder="Describe las responsabilidades y funciones principales..."></textarea></div>
                  <div class="col-12"><label for="sol-nuevo-actividades">Actividades *</label><textarea id="sol-nuevo-actividades" required placeholder="Lista de actividades principales del puesto..."></textarea></div>
                  <div class="col-12"><label for="sol-nuevo-requisitos">Requisitos</label><textarea id="sol-nuevo-requisitos" placeholder="Experiencia requerida, habilidades, certificaciones..."></textarea></div>
                  <div class="col-12"><label for="sol-nuevo-conocimientos">Conocimientos</label><textarea id="sol-nuevo-conocimientos" placeholder="Conocimientos técnicos requeridos..."></textarea></div>
                </div>
              </div>
              <div class="card">
                <div class="grid">
                  <div class="col-12"><label for="sol-nuevo-justificacion">Justificación *</label><textarea id="sol-nuevo-justificacion" required placeholder="Explica por qué se necesita este nuevo puesto..."></textarea></div>
                </div>
              </div>
              <div class="actions">
                <button type="button" class="btn btn-ghost" onclick="closeModal('nueva-solicitud')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Solicitar Aprobación</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Detalle de Solicitud -->
    <div id="modal-detalle-solicitud" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Detalle de la Solicitud</h3>
          <button class="close-modal" onclick="closeModal('detalle-solicitud')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body" id="detalle-solicitud-content"></div>
      </div>
    </div>

    <!-- Modal: Completar y Publicar Vacante -->
    <div id="modal-completar-vacante" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Completar y Publicar Vacante</h3>
          <button class="close-modal" onclick="closeModal('completar-vacante')">
            <svg class="icon" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="modal-body">
          <form id="form-completar-vacante">
            <input type="hidden" id="completar-solicitud-id">
            <div class="card">
              <div class="card-header"><h2>Información de la Solicitud</h2></div>
              <div id="completar-info"></div>
            </div>
            <div class="card">
              <div class="card-header"><h2>Datos de la Vacante (RH)</h2></div>
              <div class="grid">
                <div class="col-6"><label for="completar-ubicacion">Ubicación *</label><input id="completar-ubicacion" type="text" required placeholder="Ciudad, Estado"></div>
                <div class="col-6"><label for="completar-salario">Rango Salarial</label><input id="completar-salario" type="text" placeholder="Ej. $15,000 - $20,000"></div>
                <div class="col-12"><label for="completar-descripcion">Descripción del Puesto *</label><textarea id="completar-descripcion" required placeholder="Describe las responsabilidades y funciones principales..."></textarea></div>
                <div class="col-12"><label for="completar-actividades">Actividades</label><textarea id="completar-actividades" placeholder="Lista de actividades principales del puesto..."></textarea></div>
                <div class="col-12"><label for="completar-requisitos">Requisitos</label><textarea id="completar-requisitos" placeholder="Experiencia requerida, habilidades, certificaciones..."></textarea></div>
                <div class="col-12"><label for="completar-conocimientos">Conocimientos</label><textarea id="completar-conocimientos" placeholder="Conocimientos técnicos requeridos..."></textarea></div>
                <div class="col-12"><label for="completar-ofrecemos">Ofrecemos</label><textarea id="completar-ofrecemos" placeholder="Prestaciones, horarios, bonos..."></textarea></div>
                <div class="col-12"><label for="completar-beneficios">Beneficios</label><textarea id="completar-beneficios" placeholder="Beneficios adicionales del puesto..."></textarea></div>
              </div>
            </div>
            <div class="actions">
              <button type="button" class="btn btn-ghost" onclick="closeModal('completar-vacante')">Cancelar</button>
              <button type="submit" class="btn btn-primary">Publicar Vacante</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Sidebar: Notificaciones -->
    <div id="sidebar-notificaciones" class="sidebar-notif">
      <div class="sidebar-notif-header">
        <h3>Notificaciones</h3>
        <button onclick="toggleNotificaciones()">&times;</button>
      </div>
      <div id="notificaciones-lista"></div>
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

  </div><!-- /.vacantes-module -->

  <script>
    window.__VACANTES_CONFIG = {
      rol: <?= json_encode($vacantesRol) ?>,
      dobleRol: <?= json_encode($dobleRol || !empty($allRoles)) ?>,
      allRoles: <?= json_encode(!empty($allRoles)) ?>,
      userName: <?= json_encode($userName) ?>,
      rolNombre: <?= json_encode($rolNombre) ?>,
      portalUrl: <?= json_encode(base_url('vacantes/portal')) ?>,
      mipostulacionUrl: <?= json_encode(base_url('vacantes/mipostulacion')) ?>,
      logoutUrl: <?= json_encode(base_url('auth/signout')) ?>
    };
  </script>
  <script src="<?= base_url('js/vacantes.js') ?>"></script>
</div>
</body>
</html>
