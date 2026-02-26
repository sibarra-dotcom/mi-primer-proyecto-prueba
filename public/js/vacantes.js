// ==================== DATA STORAGE ====================
let vacantes = JSON.parse(localStorage.getItem('vacantes') || '[]');
let candidatos = JSON.parse(localStorage.getItem('candidatos') || '[]');
let entrevistas = JSON.parse(localStorage.getItem('entrevistas') || '[]');
let solicitudes = JSON.parse(localStorage.getItem('solicitudes') || '[]');
let empleados = JSON.parse(localStorage.getItem('empleados') || '[]');
let notificaciones = JSON.parse(localStorage.getItem('notificaciones') || '[]');
let rolActual = (window.__VACANTES_CONFIG && window.__VACANTES_CONFIG.rol) || 'rh';
let sesionUsuario = window.__VACANTES_CONFIG || null;

// ==================== RECLUTADORAS CONFIG ====================
const RECLUTADORAS = [
  { id: 'rec-jennifer', nombre: 'Jennifer De La Rosa', nombreCorto: 'Jennifer',
    email: 'j.delarosa@gibanibb.com', color: '#007940', colorLight: '#e6f4ed' },
  { id: 'rec-lizbeth', nombre: 'Lizbeth Mendez', nombreCorto: 'Lizbeth',
    email: 'l.mendez@gibanibb.com', color: '#6366f1', colorLight: '#eef2ff' }
];

const CALENDARIO_CONFIG = {
  horaInicio: 9, horaFin: 17, slotMinutos: 30,
  diasSemana: ['Lunes','Martes','Mi\u00e9rcoles','Jueves','Viernes']
};

// ==================== DASHBOARD CHARTS ====================
let rhFunnelChart = null;
let rhBarChart = null;
let rhGaugeChart = null;
let rhLineChart = null;
let rhRejectionChart = null;

const RH_CHART_HEIGHTS = {
  funnel: 380,
  bar: 284,
  gauge: 350,
  line: 332
};

function saveData() {
  localStorage.setItem('vacantes', JSON.stringify(vacantes));
  localStorage.setItem('candidatos', JSON.stringify(candidatos));
  localStorage.setItem('entrevistas', JSON.stringify(entrevistas));
  localStorage.setItem('solicitudes', JSON.stringify(solicitudes));
  localStorage.setItem('empleados', JSON.stringify(empleados));
  localStorage.setItem('notificaciones', JSON.stringify(notificaciones));
}

// ==================== EMPLEADOS ====================
function getPuestosUnicos(departamento) {
  const empleadosDepto = empleados.filter(e => e.departamento === departamento);
  const puestosMap = {};
  empleadosDepto.forEach(e => {
    if (!puestosMap[e.puesto]) {
      puestosMap[e.puesto] = {
        puesto: e.puesto,
        descripcionPuesto: e.descripcionPuesto,
        requisitosPuesto: e.requisitosPuesto,
        tipoContrato: e.tipoContrato,
        departamento: e.departamento
      };
    }
  });
  return Object.values(puestosMap);
}

// ==================== ROLES ====================
function esRolJefe() {
  return rolActual.startsWith('jefe-');
}

function getDepartamentoJefe() {
  if (!esRolJefe()) return null;
  const depto = rolActual.replace('jefe-', '');
  const map = {
    'it': 'IT',
    'ventas': 'Ventas',
    'marketing': 'Marketing',
    'operaciones': 'Operaciones',
    'finanzas': 'Finanzas'
  };
  return map[depto] || depto;
}

function cambiarRol(nuevoRol) {
  rolActual = nuevoRol;
  const roleEl = document.getElementById('user-role');
  const selectEl = document.getElementById('select-rol-doble');
  if (roleEl && selectEl) {
    const opt = selectEl.options[selectEl.selectedIndex];
    if (opt) roleEl.textContent = opt.text;
  }
  aplicarVistasPorRol();
}

// ==================== AUTH ====================
async function verificarSesion() {
  const cfg = window.__VACANTES_CONFIG;
  if (!cfg) {
    console.warn('No se encontró __VACANTES_CONFIG');
    return true;
  }
  sesionUsuario = cfg;
  rolActual = cfg.rol || 'rh';

  // Inicializar user-info en el navbar del header
  const wrapper = document.getElementById('vacantes-user-info');
  const nameEl = document.getElementById('user-name');
  const roleEl = document.getElementById('user-role');
  if (nameEl) nameEl.textContent = cfg.userName || '';
  if (roleEl) roleEl.textContent = cfg.rolNombre || '';

  // Poblar y mostrar selector de roles
  if (cfg.dobleRol && cfg.rolNombres) {
    const selectDoble = document.getElementById('select-rol-doble');
    if (selectDoble) {
      selectDoble.innerHTML = '';
      for (const [key, label] of Object.entries(cfg.rolNombres)) {
        const opt = document.createElement('option');
        opt.value = key;
        opt.textContent = label;
        if (key === rolActual) opt.selected = true;
        selectDoble.appendChild(opt);
      }
      selectDoble.style.display = '';
    }
  }

  // Mostrar el bloque de user-info en el header solo para admin
  if (wrapper && cfg.allRoles) wrapper.style.display = 'flex';

  return true;
}

async function cerrarSesion() {
  const cfg = window.__VACANTES_CONFIG;
  window.location.href = (cfg && cfg.logoutUrl) || '/';
}

function aplicarVistasPorRol() {
  const navsRH = document.querySelectorAll('.nav-rh');
  const navsJefe = document.querySelectorAll('.nav-jefe');
  const navsAprobacion = document.querySelectorAll('.nav-aprobacion');

  navsRH.forEach(el => el.style.display = 'none');
  navsJefe.forEach(el => el.style.display = 'none');
  navsAprobacion.forEach(el => el.style.display = 'none');

  if (esRolJefe()) {
    navsJefe.forEach(el => el.style.display = '');
    showView('solicitudes-jefe');
  } else if (rolActual === 'gerente-finanzas' || rolActual === 'gerente-do') {
    navsAprobacion.forEach(el => el.style.display = '');
    showView('solicitudes-aprobacion');
  } else {
    navsRH.forEach(el => el.style.display = '');
    // Expandir sección Vacantes en mobile por defecto
    const mobileVacantesToggle = document.querySelector('.mobile-section-vacantes')?.closest('ul')?.querySelector('.mobile-section-toggle:not([disabled])');
    if (mobileVacantesToggle && !mobileVacantesToggle.classList.contains('open')) {
      mobileVacantesToggle.classList.add('open');
      document.querySelectorAll('.mobile-section-vacantes').forEach(el => el.classList.add('mobile-section-open'));
    }
    showView('gestion-dashboard');
  }

  contarNoLeidas();
}

// ==================== DROPDOWN MENUS ====================
function toggleDropdown(dropdownId) {
  const dd = document.getElementById(dropdownId);
  if (!dd) return;
  const wasOpen = dd.classList.contains('open');
  // Cerrar todos los dropdowns primero
  document.querySelectorAll('.nav-dropdown.open').forEach(d => d.classList.remove('open'));
  if (!wasOpen) dd.classList.add('open');
}

// Cerrar dropdowns al hacer clic fuera
document.addEventListener('click', function(e) {
  if (!e.target.closest('.nav-dropdown')) {
    document.querySelectorAll('.nav-dropdown.open').forEach(d => d.classList.remove('open'));
  }
});

// Mobile sections toggle
function toggleMobileSection(btn) {
  if (btn.disabled) return;
  btn.classList.toggle('open');
  const sectionLi = btn.closest('li');
  const isOpen = btn.classList.contains('open');
  // Buscar los items de la sección (siguientes li con mobile-section-item)
  let next = sectionLi.nextElementSibling;
  while (next && next.classList.contains('mobile-section-item')) {
    next.classList.toggle('mobile-section-open', isOpen);
    next = next.nextElementSibling;
  }
}

// ==================== NAVIGATION ====================
function showView(viewName) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  const target = document.getElementById(`view-${viewName}`);
  if (!target) return;
  target.classList.add('active');

  // Actualizar estado activo en navbar (items del dropdown)
  document.querySelectorAll('.nav-link-vac').forEach(link => link.classList.remove('nav-active'));
  const activeLinks = document.querySelectorAll(`.nav-link-vac[data-view="${viewName}"]`);
  activeLinks.forEach(link => link.classList.add('nav-active'));

  // Actualizar estado activo del toggle padre del dropdown
  document.querySelectorAll('.nav-dropdown-toggle').forEach(t => t.classList.remove('nav-parent-active'));
  const activeDropdownItem = document.querySelector(`.nav-dropdown-item.nav-active`);
  if (activeDropdownItem) {
    const parentToggle = activeDropdownItem.closest('.nav-dropdown')?.querySelector('.nav-dropdown-toggle');
    if (parentToggle) parentToggle.classList.add('nav-parent-active');
  }

  // Cerrar dropdown al seleccionar una vista
  document.querySelectorAll('.nav-dropdown.open').forEach(d => d.classList.remove('open'));

  // Dashboard usa ancho completo
  const mainEl = document.querySelector('.vacantes-module main');
  if (mainEl) {
    if (viewName === 'gestion-dashboard') {
      mainEl.classList.add('dashboard-active');
    } else {
      mainEl.classList.remove('dashboard-active');
    }
  }

  // Cerrar menú móvil si está abierto
  const mobileCheckbox = document.getElementById('mobile_checkbox');
  if (mobileCheckbox) mobileCheckbox.checked = false;

  if (viewName === 'gestion-vacantes') {
    renderVacantesGestion();
    populateVacanteFilters();
    renderSolicitudesPreaprobadas();
  } else if (viewName === 'gestion-candidatos') {
    renderCandidatosTable();
    populateCandidatoFilters();
  } else if (viewName === 'gestion-dashboard') {
    renderDashboard();
  } else if (viewName === 'calendario-reclutadoras') {
    renderCalendarioReclutadoras();
  } else if (viewName === 'solicitudes-jefe') {
    renderSolicitudesJefe();
  } else if (viewName === 'solicitudes-aprobacion') {
    renderSolicitudesAprobacion();
  }
}

// ==================== MODALS ====================
function openModal(modalName) {
  document.getElementById(`modal-${modalName}`).classList.add('show');
  if (modalName === 'nueva-vacante') {
    poblarSelectorReclutadoraVacante();
  }
}

function poblarSelectorReclutadoraVacante() {
  var sel = document.getElementById('vac-reclutadora');
  if (!sel) return;
  sel.innerHTML = '<option value="">Sin asignar</option>';
  RECLUTADORAS.forEach(function(r) {
    var opt = document.createElement('option');
    opt.value = r.id;
    opt.textContent = r.nombre;
    sel.appendChild(opt);
  });
}

function closeModal(modalName) {
  document.getElementById(`modal-${modalName}`).classList.remove('show');
}

// ==================== TOAST ====================
function showToast(title, detail = '') {
  const toast = document.getElementById('toast');
  toast.innerHTML = `<strong>${escapeHtml(title)}</strong>${detail ? '<small>' + escapeHtml(detail) + '</small>' : ''}`;
  toast.classList.add('show');

  clearTimeout(window.__toastTimer);
  window.__toastTimer = setTimeout(() => toast.classList.remove('show'), 3200);
}

function escapeHtml(str) {
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function formatFecha(fechaISO) {
  if (!fechaISO) return 'Sin fecha';
  const d = new Date(fechaISO + (fechaISO.length === 10 ? 'T12:00:00' : ''));
  const dd = String(d.getDate()).padStart(2, '0');
  const mm = String(d.getMonth() + 1).padStart(2, '0');
  const aaaa = d.getFullYear();
  return `${dd}/${mm}/${aaaa}`;
}

function generarCodigoSeguimiento() {
  const existentes = new Set(candidatos.map(c => c.codigoSeguimiento).filter(Boolean));
  let codigo;
  do {
    codigo = String(Math.floor(100000 + Math.random() * 900000));
  } while (existentes.has(codigo));
  return codigo;
}

function generarCodigoVacante() {
  var maxNum = 0;
  vacantes.forEach(function(v) {
    if (v.codigo) {
      var num = parseInt(v.codigo.replace('VAC-', ''), 10);
      if (num > maxNum) maxNum = num;
    }
  });
  return 'VAC-' + String(maxNum + 1).padStart(4, '0');
}

// ==================== CALENDARIO EN ESPAÑOL ====================
const MESES_ES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
const DIAS_SEMANA_ES = ['Lu','Ma','Mi','Ju','Vi','Sá','Do'];

function crearCalendarioDropdown() {
  const dd = document.createElement('div');
  dd.className = 'calendario-dropdown';
  dd.id = 'calendario-dropdown';
  dd.style.display = 'none';
  dd.innerHTML = `
    <div class="cal-header">
      <button type="button" class="cal-nav" id="cal-nav-prev" onclick="calNavPrev()">&lsaquo;</button>
      <span class="cal-title"><span id="cal-titulo-mes" class="cal-mes-clickable" onclick="mostrarSelectorMes()"></span> <span id="cal-titulo-año" class="cal-año-clickable" onclick="mostrarSelectorAnio()"></span></span>
      <button type="button" class="cal-nav" id="cal-nav-next" onclick="calNavNext()">&rsaquo;</button>
    </div>
    <div class="cal-dias-header" id="cal-dias-header-wrap">${DIAS_SEMANA_ES.map(d => `<span>${d}</span>`).join('')}</div>
    <div class="cal-dias" id="cal-dias"></div>
    <div class="cal-meses-grid" id="cal-meses-grid" style="display:none;"></div>
    <div class="cal-años-grid" id="cal-años-grid" style="display:none;"></div>
    <div class="cal-footer" id="cal-footer-wrap">
      <button type="button" class="cal-hoy" onclick="calHoy()">Hoy</button>
      <button type="button" class="cal-limpiar" onclick="calLimpiar()">Limpiar</button>
    </div>
  `;
  const vmContainer = document.querySelector('.vacantes-module') || document.body;
  vmContainer.appendChild(dd);
  window.__calDropdown = dd;
  window.__calInputActivo = null;
  window.__calAnioActual = null;
  window.__calMesActual = null;
  window.__calVistaAnios = false;
  window.__calVistaMeses = false;
  window.__calAniosPagInicio = null;

  document.addEventListener('click', function(e) {
    if (!window.__calDropdown) return;
    if (!window.__calDropdown.classList.contains('show')) return;
    if (window.__calDropdown.contains(e.target)) return;
    if (e.target.closest && e.target.closest('.fecha-cal-btn')) return;
    cerrarCalendario();
  });
}

function toggleCalendario(btn) {
  const wrapper = btn.closest('.fecha-input-wrapper');
  const input = wrapper.querySelector('input');
  const dd = window.__calDropdown;

  if (dd.classList.contains('show') && window.__calInputActivo === input) {
    cerrarCalendario();
    return;
  }

  window.__calInputActivo = input;

  // Posicionar el dropdown debajo del wrapper
  const rect = wrapper.getBoundingClientRect();
  dd.style.position = 'fixed';
  dd.style.top = rect.bottom + 4 + 'px';
  dd.style.left = rect.left + 'px';

  // Determinar mes/año a mostrar
  let año, mes;
  const val = input.value;
  if (val && val.length === 10) {
    const partes = val.split('/');
    if (partes.length === 3) {
      mes = parseInt(partes[1], 10) - 1;
      año = parseInt(partes[2], 10);
    }
  }
  if (año === undefined || isNaN(año)) {
    const hoy = new Date();
    año = hoy.getFullYear();
    mes = hoy.getMonth();
  }

  window.__calAnioActual = año;
  window.__calMesActual = mes;

  renderMesCalendario(año, mes);
  dd.style.display = 'block';
  dd.classList.add('show');
}

function cerrarCalendario() {
  if (window.__calDropdown) {
    window.__calDropdown.classList.remove('show');
    window.__calDropdown.style.display = 'none';
  }
}

function renderMesCalendario(año, mes) {
  window.__calVistaAnios = false;
  window.__calVistaMeses = false;
  document.getElementById('cal-titulo-mes').textContent = MESES_ES[mes];
  document.getElementById('cal-titulo-año').textContent = año;
  // Asegurar que la vista de días esté visible
  document.getElementById('cal-dias-header-wrap').style.display = '';
  document.getElementById('cal-dias').style.display = '';
  document.getElementById('cal-footer-wrap').style.display = '';
  document.getElementById('cal-años-grid').style.display = 'none';
  document.getElementById('cal-meses-grid').style.display = 'none';
  document.getElementById('cal-nav-prev').style.visibility = '';
  document.getElementById('cal-nav-next').style.visibility = '';

  const primerDia = new Date(año, mes, 1);
  const ultimoDia = new Date(año, mes + 1, 0);
  const diasEnMes = ultimoDia.getDate();

  // Lunes=0, Domingo=6
  let diaInicio = primerDia.getDay() - 1;
  if (diaInicio < 0) diaInicio = 6;

  const hoy = new Date();
  const hoyDia = hoy.getDate();
  const hoyMes = hoy.getMonth();
  const hoyAnio = hoy.getFullYear();

  // Día seleccionado del input
  let selDia = -1, selMes = -1, selAnio = -1;
  if (window.__calInputActivo) {
    const val = window.__calInputActivo.value;
    if (val && val.length === 10) {
      const p = val.split('/');
      if (p.length === 3) {
        selDia = parseInt(p[0], 10);
        selMes = parseInt(p[1], 10) - 1;
        selAnio = parseInt(p[2], 10);
      }
    }
  }

  const diasContainer = document.getElementById('cal-dias');
  let html = '';

  // Días del mes anterior
  const mesAnteriorUltimo = new Date(año, mes, 0).getDate();
  for (let i = diaInicio - 1; i >= 0; i--) {
    const d = mesAnteriorUltimo - i;
    const mAnt = mes === 0 ? 11 : mes - 1;
    const aAnt = mes === 0 ? año - 1 : año;
    html += `<button type="button" class="cal-dia otro-mes" onclick="seleccionarDiaCalendario(${d},${mAnt},${aAnt})">${d}</button>`;
  }

  // Días del mes actual
  for (let d = 1; d <= diasEnMes; d++) {
    let clases = 'cal-dia';
    if (d === hoyDia && mes === hoyMes && año === hoyAnio) clases += ' hoy';
    if (d === selDia && mes === selMes && año === selAnio) clases += ' seleccionado';
    html += `<button type="button" class="${clases}" onclick="seleccionarDiaCalendario(${d},${mes},${año})">${d}</button>`;
  }

  // Días del mes siguiente para completar la grilla
  const totalCeldas = diaInicio + diasEnMes;
  const restantes = totalCeldas % 7 === 0 ? 0 : 7 - (totalCeldas % 7);
  for (let d = 1; d <= restantes; d++) {
    const mSig = mes === 11 ? 0 : mes + 1;
    const aSig = mes === 11 ? año + 1 : año;
    html += `<button type="button" class="cal-dia otro-mes" onclick="seleccionarDiaCalendario(${d},${mSig},${aSig})">${d}</button>`;
  }

  diasContainer.innerHTML = html;
}

function seleccionarDiaCalendario(dia, mes, año) {
  const dd = String(dia).padStart(2, '0');
  const mm = String(mes + 1).padStart(2, '0');
  const valor = `${dd}/${mm}/${año}`;

  if (window.__calInputActivo) {
    window.__calInputActivo.value = valor;
    window.__calInputActivo.dispatchEvent(new Event('input', { bubbles: true }));
  }
  cerrarCalendario();
}

function mostrarSelectorMes() {
  window.__calVistaMeses = true;
  window.__calVistaAnios = false;
  document.getElementById('cal-dias-header-wrap').style.display = 'none';
  document.getElementById('cal-dias').style.display = 'none';
  document.getElementById('cal-footer-wrap').style.display = 'none';
  document.getElementById('cal-años-grid').style.display = 'none';
  // Flechas para cambiar año mientras se eligen meses
  document.getElementById('cal-nav-prev').style.visibility = '';
  renderSelectorMes();
}

function renderSelectorMes() {
  const grid = document.getElementById('cal-meses-grid');
  const mesSel = window.__calMesActual;
  const año = window.__calAnioActual;
  const hoy = new Date();
  const hoyMes = hoy.getMonth();
  const hoyAnio = hoy.getFullYear();

  document.getElementById('cal-titulo-mes').textContent = '';
  document.getElementById('cal-titulo-año').textContent = año;

  // Ocultar → si el año ya es el actual (no avanzar más)
  document.getElementById('cal-nav-next').style.visibility = año >= hoyAnio ? 'hidden' : '';

  const MESES_CORTOS = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
  let html = '';
  for (let m = 0; m < 12; m++) {
    let clases = 'cal-mes-item';
    if (m === mesSel && !window.__calVistaAnios) clases += ' seleccionado';
    if (m === hoyMes && año === hoyAnio) clases += ' hoy';
    const esFuturo = año === hoyAnio && m > hoyMes;
    if (esFuturo) clases += ' disabled';
    const disabled = esFuturo ? ' disabled' : '';
    html += `<button type="button" class="${clases}"${disabled} onclick="seleccionarMes(${m})">${MESES_CORTOS[m]}</button>`;
  }
  grid.innerHTML = html;
  grid.style.display = 'grid';
}

function seleccionarMes(mes) {
  window.__calVistaMeses = false;
  window.__calMesActual = mes;
  renderMesCalendario(window.__calAnioActual, mes);
}

function mostrarSelectorAnio() {
  window.__calVistaAnios = true;
  window.__calVistaMeses = false;
  document.getElementById('cal-dias-header-wrap').style.display = 'none';
  document.getElementById('cal-dias').style.display = 'none';
  document.getElementById('cal-footer-wrap').style.display = 'none';
  document.getElementById('cal-meses-grid').style.display = 'none';

  // Página de 12 años centrada en el año seleccionado
  const inicio = window.__calAnioActual - 5;
  window.__calAniosPagInicio = inicio;
  renderSelectorAnio();
}

function renderSelectorAnio() {
  const grid = document.getElementById('cal-años-grid');
  const añoSel = window.__calAnioActual;
  const hoyAnio = new Date().getFullYear();
  const inicio = window.__calAniosPagInicio;
  const fin = inicio + 11;

  // Actualizar título con rango
  document.getElementById('cal-titulo-mes').textContent = inicio + ' - ' + Math.min(fin, hoyAnio);
  document.getElementById('cal-titulo-año').textContent = '';

  // Ocultar flecha → si la página ya contiene el año actual
  document.getElementById('cal-nav-next').style.visibility = fin >= hoyAnio ? 'hidden' : '';

  let html = '';
  for (let a = inicio; a <= fin; a++) {
    let clases = 'cal-año-item';
    if (a === añoSel) clases += ' seleccionado';
    if (a === hoyAnio) clases += ' hoy';
    if (a > hoyAnio) clases += ' disabled';
    const disabled = a > hoyAnio ? ' disabled' : '';
    html += `<button type="button" class="${clases}"${disabled} onclick="seleccionarAnio(${a})">${a}</button>`;
  }
  grid.innerHTML = html;
  grid.style.display = 'grid';
}

function seleccionarAnio(año) {
  window.__calVistaAnios = false;
  window.__calAnioActual = año;
  document.getElementById('cal-nav-next').style.visibility = '';
  renderMesCalendario(año, window.__calMesActual);
}

function calNavPrev() {
  if (window.__calVistaAnios) {
    window.__calAniosPagInicio -= 12;
    renderSelectorAnio();
  } else if (window.__calVistaMeses) {
    window.__calAnioActual--;
    renderSelectorMes();
  } else {
    let mes = window.__calMesActual - 1;
    let año = window.__calAnioActual;
    if (mes < 0) { mes = 11; año--; }
    window.__calMesActual = mes;
    window.__calAnioActual = año;
    renderMesCalendario(año, mes);
  }
}

function calNavNext() {
  if (window.__calVistaAnios) {
    const hoyAnio = new Date().getFullYear();
    if (window.__calAniosPagInicio + 12 <= hoyAnio) {
      window.__calAniosPagInicio += 12;
      renderSelectorAnio();
    }
  } else if (window.__calVistaMeses) {
    const hoyAnio = new Date().getFullYear();
    if (window.__calAnioActual < hoyAnio) {
      window.__calAnioActual++;
      renderSelectorMes();
    }
  } else {
    let mes = window.__calMesActual + 1;
    let año = window.__calAnioActual;
    if (mes > 11) { mes = 0; año++; }
    window.__calMesActual = mes;
    window.__calAnioActual = año;
    renderMesCalendario(año, mes);
  }
}

function calHoy() {
  const hoy = new Date();
  seleccionarDiaCalendario(hoy.getDate(), hoy.getMonth(), hoy.getFullYear());
}

function calLimpiar() {
  if (window.__calInputActivo) {
    window.__calInputActivo.value = '';
    window.__calInputActivo.dispatchEvent(new Event('input', { bubbles: true }));
  }
  cerrarCalendario();
}

// ==================== MÁSCARA DE FECHA ====================
function mascaraFecha(input) {
  let v = input.value.replace(/\D/g, '');
  if (v.length > 8) v = v.substring(0, 8);
  if (v.length >= 5) {
    v = v.substring(0, 2) + '/' + v.substring(2, 4) + '/' + v.substring(4);
  } else if (v.length >= 3) {
    v = v.substring(0, 2) + '/' + v.substring(2);
  }
  input.value = v;
}

function fechaDDMMAAAAaISO(ddmmaaaa) {
  if (!ddmmaaaa || ddmmaaaa.length !== 10) return '';
  const partes = ddmmaaaa.split('/');
  if (partes.length !== 3) return '';
  return `${partes[2]}-${partes[1]}-${partes[0]}`;
}

function fechaISOaDDMMAAAA(iso) {
  if (!iso || iso.length < 10) return '';
  const partes = iso.substring(0, 10).split('-');
  if (partes.length !== 3) return '';
  return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

function configurarValidacionEspanol() {
  document.querySelectorAll('[required]').forEach(el => {
    el.addEventListener('invalid', function() {
      if (this.validity.valueMissing) {
        this.setCustomValidity('Por favor, completa este campo.');
      } else if (this.validity.typeMismatch) {
        this.setCustomValidity('Por favor, ingresa un valor v\u00e1lido.');
      } else if (this.validity.patternMismatch) {
        this.setCustomValidity(this.title || 'El formato no es v\u00e1lido.');
      } else {
        this.setCustomValidity('');
      }
    });
    el.addEventListener('input', function() {
      this.setCustomValidity('');
    });
  });
}

// ==================== CONFETTI ====================
let __celebrated = false;
let __pendingCodigoSeguimiento = null;

function celebrate() {
  document.getElementById('celebration').classList.add('show');
  launchConfetti();

  clearTimeout(window.__celebrateTimer);
  window.__celebrateTimer = setTimeout(() => {
    cerrarCelebracion();
  }, 10000);
}

function cerrarCelebracion() {
  clearTimeout(window.__celebrateTimer);
  document.getElementById('celebration').classList.remove('show');
  const layer = document.getElementById('confettiLayer');
  if (layer) layer.innerHTML = '';

  if (__pendingCodigoSeguimiento) {
    const codigo = __pendingCodigoSeguimiento;
    __pendingCodigoSeguimiento = null;
    mostrarCodigoSeguimiento(codigo);
  }
}

function mostrarCodigoSeguimiento(codigo) {
  const el = document.getElementById('codigo-seguimiento-display');
  if (el) el.textContent = codigo;
  const modal = document.getElementById('modal-codigo-seguimiento');
  if (modal) modal.classList.add('show');
}

function cerrarModalCodigo() {
  const modal = document.getElementById('modal-codigo-seguimiento');
  if (modal) modal.classList.remove('show');
}

function copiarCodigoSeguimiento() {
  const el = document.getElementById('codigo-seguimiento-display');
  if (!el) return;
  const codigo = el.textContent;
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(codigo).then(() => {
      showToast('Código copiado', 'Se copió al portapapeles');
    }).catch(() => {
      fallbackCopiar(codigo);
    });
  } else {
    fallbackCopiar(codigo);
  }
}

function fallbackCopiar(texto) {
  const ta = document.createElement('textarea');
  ta.value = texto;
  ta.style.position = 'fixed';
  ta.style.left = '-9999px';
  document.body.appendChild(ta);
  ta.select();
  try {
    document.execCommand('copy');
    showToast('Código copiado', 'Se copió al portapapeles');
  } catch (_) {
    showToast('No se pudo copiar', 'Copia el código manualmente');
  }
  document.body.removeChild(ta);
}

function launchConfetti() {
  const layer = document.getElementById('confettiLayer');
  layer.innerHTML = '';

  const colors = ['#007940', '#22c55e', '#f59e0b', '#3b82f6', '#ef4444', '#ffffff'];
  const pieces = 140;

  for (let i = 0; i < pieces; i++) {
    const p = document.createElement('div');
    p.className = 'confetti-piece';

    const left = Math.random() * 100;
    const xDrift = (Math.random() * 140 - 70);
    const dur = 1400 + Math.random() * 1200;
    const sizeW = 6 + Math.random() * 8;
    const sizeH = 8 + Math.random() * 14;

    p.style.left = left + 'vw';
    p.style.setProperty('--x', xDrift + 'px');
    p.style.setProperty('--dur', dur + 'ms');
    p.style.width = sizeW + 'px';
    p.style.height = sizeH + 'px';
    p.style.background = colors[Math.floor(Math.random() * colors.length)];

    layer.appendChild(p);
  }

  clearTimeout(window.__confettiTimer);
  window.__confettiTimer = setTimeout(() => {
    layer.innerHTML = '';
  }, 10500);
}

// ==================== VACANTES ====================
var vistaModos = {};

function toggleVistaGrid(seccion) {
  var modo = vistaModos[seccion] === 'lista' ? 'grid' : 'lista';
  setVistaSeccion(seccion, modo);
}

function setVistaSeccion(seccion, modo) {
  vistaModos[seccion] = modo;
  var btnGrid = document.getElementById('vista-grid-' + seccion);
  var btnLista = document.getElementById('vista-lista-' + seccion);
  if (btnGrid) btnGrid.classList.toggle('active', modo === 'grid');
  if (btnLista) btnLista.classList.toggle('active', modo === 'lista');
  // Re-render la sección correspondiente
  if (seccion === 'vacantesGestionGrid') filtrarVacantes();
  else if (seccion === 'preaprobadasGrid') renderSolicitudesPreaprobadas();
  else if (seccion === 'solicitudesJefeGrid') renderSolicitudesJefe();
  else if (seccion === 'solicitudesAprobacionPendientes' || seccion === 'solicitudesAprobacionRevisadas') renderSolicitudesAprobacion();
}

var portalVistaActual = 'grid';

function setPortalVista(modo) {
  portalVistaActual = modo;
  document.getElementById('portal-vista-grid').classList.toggle('active', modo === 'grid');
  document.getElementById('portal-vista-lista').classList.toggle('active', modo === 'lista');
  renderVacantesPortal();
}

function poblarFiltrosPortal() {
  var abiertas = vacantes.filter(function(v) { return v.estado === 'abierta'; });

  var deptos = [];
  var ubics = [];
  var jornadas = [];
  abiertas.forEach(function(v) {
    if (v.departamento && deptos.indexOf(v.departamento) === -1) deptos.push(v.departamento);
    var ubNombre = v.ubicacionClave ? (UBICACION_NOMBRES[v.ubicacionClave] || v.ubicacion) : v.ubicacion;
    if (ubNombre && ubics.indexOf(ubNombre) === -1) ubics.push(ubNombre);
    var jorn = v.jornada || v.tipo;
    if (jorn && jornadas.indexOf(jorn) === -1) jornadas.push(jorn);
  });
  deptos.sort();
  ubics.sort();
  jornadas.sort();

  var selDepto = document.getElementById('portal-filtro-depto');
  var selUbic = document.getElementById('portal-filtro-ubicacion');
  var selJorn = document.getElementById('portal-filtro-jornada');
  if (selDepto) {
    selDepto.innerHTML = '<option value="">Departamento</option>' + deptos.map(function(d) { return '<option>' + escapeHtml(d) + '</option>'; }).join('');
  }
  if (selUbic) {
    selUbic.innerHTML = '<option value="">Ubicación</option>' + ubics.map(function(u) { return '<option>' + escapeHtml(u) + '</option>'; }).join('');
  }
  if (selJorn) {
    selJorn.innerHTML = '<option value="">Jornada</option>' + jornadas.map(function(j) { return '<option>' + escapeHtml(j) + '</option>'; }).join('');
  }
}

function setupFiltrosPortal() {
  var ids = ['portal-busqueda', 'portal-filtro-depto', 'portal-filtro-ubicacion', 'portal-filtro-jornada', 'portal-ordenar'];
  ids.forEach(function(id) {
    var el = document.getElementById(id);
    if (el) el.addEventListener(id === 'portal-busqueda' ? 'input' : 'change', renderVacantesPortal);
  });
}

function limpiarFiltrosPortal() {
  var busq = document.getElementById('portal-busqueda');
  var depto = document.getElementById('portal-filtro-depto');
  var ubic = document.getElementById('portal-filtro-ubicacion');
  var jorn = document.getElementById('portal-filtro-jornada');
  if (busq) busq.value = '';
  if (depto) depto.value = '';
  if (ubic) ubic.value = '';
  if (jorn) jorn.value = '';
  renderVacantesPortal();
}

function actualizarBotonLimpiar() {
  var busqueda = (document.getElementById('portal-busqueda') || {}).value || '';
  var filtroDepto = (document.getElementById('portal-filtro-depto') || {}).value || '';
  var filtroUbic = (document.getElementById('portal-filtro-ubicacion') || {}).value || '';
  var filtroJorn = (document.getElementById('portal-filtro-jornada') || {}).value || '';
  var hayFiltros = !!(busqueda || filtroDepto || filtroUbic || filtroJorn);
  var btn = document.getElementById('portal-limpiar');
  if (btn) btn.style.display = hayFiltros ? '' : 'none';
}

function renderVacantesPortal() {
  var grid = document.getElementById('vacantesGrid');
  if (!grid) return;
  var vacantesAbiertas = vacantes.filter(function(v) { return v.estado === 'abierta'; });

  // Filtros
  var busqueda = (document.getElementById('portal-busqueda') || {}).value || '';
  var filtroDepto = (document.getElementById('portal-filtro-depto') || {}).value || '';
  var filtroUbic = (document.getElementById('portal-filtro-ubicacion') || {}).value || '';
  var filtroJorn = (document.getElementById('portal-filtro-jornada') || {}).value || '';
  var orden = (document.getElementById('portal-ordenar') || {}).value || 'reciente';

  var totalAbiertas = vacantesAbiertas.length;

  if (busqueda) {
    var q = busqueda.toLowerCase();
    vacantesAbiertas = vacantesAbiertas.filter(function(v) {
      return (v.titulo && v.titulo.toLowerCase().indexOf(q) !== -1) ||
             (v.departamento && v.departamento.toLowerCase().indexOf(q) !== -1) ||
             (v.descripcion && v.descripcion.toLowerCase().indexOf(q) !== -1) ||
             (v.codigo && v.codigo.toLowerCase().indexOf(q) !== -1);
    });
  }
  if (filtroDepto) {
    vacantesAbiertas = vacantesAbiertas.filter(function(v) { return v.departamento === filtroDepto; });
  }
  if (filtroUbic) {
    vacantesAbiertas = vacantesAbiertas.filter(function(v) {
      var ubNombre = v.ubicacionClave ? (UBICACION_NOMBRES[v.ubicacionClave] || v.ubicacion) : v.ubicacion;
      return ubNombre === filtroUbic;
    });
  }
  if (filtroJorn) {
    vacantesAbiertas = vacantesAbiertas.filter(function(v) { return (v.jornada || v.tipo) === filtroJorn; });
  }

  // Ordenar
  vacantesAbiertas.sort(function(a, b) {
    if (orden === 'reciente') return (b.fechaCreacion || '').localeCompare(a.fechaCreacion || '');
    if (orden === 'antigua') return (a.fechaCreacion || '').localeCompare(b.fechaCreacion || '');
    if (orden === 'az') return (a.titulo || '').localeCompare(b.titulo || '');
    if (orden === 'za') return (b.titulo || '').localeCompare(a.titulo || '');
    return 0;
  });

  // Resumen y botón limpiar
  actualizarBotonLimpiar();
  var resumen = document.getElementById('portalFiltrosResumen');
  if (resumen) {
    if (busqueda || filtroDepto || filtroUbic || filtroJorn) {
      resumen.textContent = vacantesAbiertas.length + ' de ' + totalAbiertas + ' vacantes';
    } else {
      resumen.textContent = totalAbiertas + ' vacantes disponibles';
    }
  }

  // Vista toggle
  var esLista = portalVistaActual === 'lista';
  grid.classList.toggle('vista-lista', esLista);

  if (vacantesAbiertas.length === 0) {
    grid.innerHTML = '<div class="empty-state">' +
      '<svg viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' +
      '<h3>No se encontraron vacantes</h3>' +
      '<p>' + (busqueda || filtroDepto || filtroUbic || filtroJorn ? 'Intenta con otros filtros de búsqueda' : 'Por el momento no hay posiciones abiertas') + '</p>' +
      '</div>';
    return;
  }

  if (esLista) {
    grid.innerHTML = vacantesAbiertas.map(function(v) {
      return '<div class="vacante-card" onclick="verDetalleVacantePublica(' + v.id + ')" style="cursor:pointer">' +
        '<div class="vacante-card-body">' +
          (v.codigo ? '<span style="font-size:11px;font-weight:700;color:var(--primary);letter-spacing:.5px;">' + escapeHtml(v.codigo) + '</span>' : '') +
          '<h3 class="vacante-title">' + escapeHtml(v.titulo) + '</h3>' +
          '<div class="vacante-info">' +
            '<div class="vacante-info-item"><strong>Departamento:</strong> ' + escapeHtml(v.departamento) + '</div>' +
            '<div class="vacante-info-item"><strong>Ubicaci\u00f3n:</strong> ' + escapeHtml(v.ubicacionClave ? (UBICACION_NOMBRES[v.ubicacionClave] || v.ubicacion) : v.ubicacion) + '</div>' +
            '<div class="vacante-info-item"><strong>Jornada:</strong> ' + escapeHtml(v.jornada || v.tipo) + '</div>' +
            (v.salario && v.mostrarSalario !== false ? '<div class="vacante-info-item"><strong>Salario:</strong> ' + escapeHtml(v.salario) + '</div>' : '') +
          '</div>' +
          '<p class="vacante-desc">' + escapeHtml(v.descripcion).substring(0, 150) + '...</p>' +
        '</div>' +
        '<div class="vacante-card-actions">' +
          '<button class="btn btn-primary btn-small" onclick="event.stopPropagation();aplicarVacante(' + v.id + ')">Aplicar Ahora</button>' +
        '</div>' +
      '</div>';
    }).join('');
  } else {
    grid.innerHTML = vacantesAbiertas.map(function(v) {
      return '<div class="vacante-card" onclick="verDetalleVacantePublica(' + v.id + ')" style="cursor:pointer">' +
        (v.codigo ? '<span style="font-size:11px;font-weight:700;color:var(--primary);letter-spacing:.5px;">' + escapeHtml(v.codigo) + '</span>' : '') +
        '<h3 class="vacante-title">' + escapeHtml(v.titulo) + '</h3>' +
        '<div class="vacante-info">' +
          '<div class="vacante-info-item"><strong>Departamento:</strong> ' + escapeHtml(v.departamento) + '</div>' +
          '<div class="vacante-info-item"><strong>Ubicaci\u00f3n:</strong> ' + escapeHtml(v.ubicacionClave ? (UBICACION_NOMBRES[v.ubicacionClave] || v.ubicacion) : v.ubicacion) + '</div>' +
          '<div class="vacante-info-item"><strong>Jornada:</strong> ' + escapeHtml(v.jornada || v.tipo) + '</div>' +
          (v.duracion ? '<div class="vacante-info-item"><strong>Duraci\u00f3n:</strong> ' + escapeHtml(v.duracion) + '</div>' : '') +
          (v.salario && v.mostrarSalario !== false ? '<div class="vacante-info-item"><strong>Salario:</strong> ' + escapeHtml(v.salario) + '</div>' : '') +
        '</div>' +
        '<p class="vacante-desc">' + escapeHtml(v.descripcion).substring(0, 150) + '...</p>' +
        '<button class="btn btn-primary btn-small" onclick="event.stopPropagation();aplicarVacante(' + v.id + ')" style="margin-top:auto;align-self:flex-end;">Aplicar Ahora</button>' +
      '</div>';
    }).join('');
  }
}

function verDetalleVacantePublica(vacanteId) {
  const vacante = vacantes.find(v => v.id === vacanteId);
  if (!vacante) return;

  const content = `
    <div class="card">
      <div class="card-header">
        <h2>Informaci\u00f3n de la Vacante</h2>
      </div>
      <div class="grid">
        <div class="col-6">
          ${vacante.codigo ? `<p><strong>Clave de Vacante:</strong> <span style="color:var(--primary);font-weight:700;letter-spacing:.5px;">${escapeHtml(vacante.codigo)}</span></p>` : ''}
          <p><strong>T\u00edtulo:</strong> ${escapeHtml(vacante.titulo)}</p>
          <p><strong>Departamento:</strong> ${escapeHtml(vacante.departamento)}</p>
          <p><strong>Ubicaci\u00f3n:</strong> ${escapeHtml(vacante.ubicacionClave ? (UBICACION_NOMBRES[vacante.ubicacionClave] || vacante.ubicacion) : vacante.ubicacion)}</p>
          ${vacante.direccion ? `<p><strong>Direcci\u00f3n:</strong> ${escapeHtml(vacante.direccion)}</p>` : ''}
        </div>
        <div class="col-6">
          ${vacante.jornada ? `<p><strong>Jornada:</strong> ${escapeHtml(vacante.jornada)}</p>` : `<p><strong>Tipo:</strong> ${escapeHtml(vacante.tipo)}</p>`}
          ${vacante.duracion ? `<p><strong>Duraci\u00f3n:</strong> ${escapeHtml(vacante.duracion)}</p>` : ''}
          ${vacante.salario && vacante.mostrarSalario !== false ? `<p><strong>Salario:</strong> ${escapeHtml(vacante.salario)}</p>` : ''}
        </div>
        <div class="col-12">
          <p><strong>Descripci\u00f3n:</strong></p>
          <p>${escapeHtml(vacante.descripcion)}</p>
        </div>
        ${vacante.requisitos ? `
        <div class="col-12">
          <p><strong>Requisitos:</strong></p>
          <p>${escapeHtml(vacante.requisitos)}</p>
        </div>
        ` : ''}
        ${vacante.horario ? `
        <div class="col-12">
          <p><strong>Horario:</strong></p>
          ${renderHorarioReadonly(vacante.horario)}
        </div>
        ` : ''}
      </div>
    </div>
    <div class="actions">
      <button class="btn btn-primary" onclick="closeModal('detalle-vacante-publica');aplicarVacante(${vacante.id})">Aplicar a esta Vacante</button>
    </div>
  `;

  document.getElementById('detalle-vacante-publica-content').innerHTML = content;
  openModal('detalle-vacante-publica');
}

function renderVacantesGestion() {
  filtrarVacantes();
}

function filtrarVacantes() {
  const searchCodigo = document.getElementById('vac-search-codigo')?.value.toUpperCase() || '';
  const searchTerm = document.getElementById('vac-search')?.value.toLowerCase() || '';
  const filterDepartamento = document.getElementById('vac-filter-departamento')?.value || '';
  const filterEstado = document.getElementById('vac-filter-estado')?.value || '';
  const filterReclutadora = document.getElementById('vac-filter-reclutadora')?.value || '';

  let vacantesFiltradas = vacantes.filter(v => {
    const matchCodigo = !searchCodigo || (v.codigo && v.codigo.toUpperCase().includes(searchCodigo));
    const matchSearch = v.titulo.toLowerCase().includes(searchTerm);
    const matchDepartamento = !filterDepartamento || v.departamento === filterDepartamento;
    const matchEstado = !filterEstado || v.estado === filterEstado;
    const matchReclutadora = !filterReclutadora || (v.reclutadoraId || '') === filterReclutadora;

    return matchCodigo && matchSearch && matchDepartamento && matchEstado && matchReclutadora;
  });

  const grid = document.getElementById('vacantesGestionGrid');

  if (vacantesFiltradas.length === 0) {
    grid.innerHTML = `
      <div class="empty-state">
        <h3>No se encontraron vacantes</h3>
        <p>Intenta ajustar los filtros de b\u00fasqueda</p>
      </div>
    `;
    return;
  }

  var esLista = vistaModos['vacantesGestionGrid'] === 'lista';
  grid.classList.toggle('vista-lista', esLista);

  grid.innerHTML = vacantesFiltradas.map(v => {
    const candidatosCount = candidatos.filter(c => c.vacanteId === v.id).length;
    const diasPublicada = Math.floor((new Date() - new Date(v.fechaCreacion)) / (1000 * 60 * 60 * 24));

    if (esLista) {
      return `
      <div class="vacante-card" onclick="verDetalleVacante(${v.id})" style="cursor:pointer">
        <div class="vacante-card-body">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <span style="font-size:11px;font-weight:700;color:var(--primary);letter-spacing:.5px;">${escapeHtml(v.codigo || '')}</span>
            <h3 class="vacante-title">${escapeHtml(v.titulo)}</h3>
            <span class="vacante-status status-${v.estado}" style="margin-left:auto;">${v.estado === 'abierta' ? 'ABIERTA' : 'CERRADA'}</span>
          </div>
          <div class="vacante-info">
            <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(v.departamento)}</div>
            <div class="vacante-info-item"><strong>Candidatos:</strong> ${candidatosCount}</div>
            <div class="vacante-info-item"><strong>Publicada:</strong> ${formatFecha(v.fechaCreacion)} (${diasPublicada}d)</div>
            ${(() => { var rec = v.reclutadoraId ? RECLUTADORAS.find(r => r.id === v.reclutadoraId) : null; return rec ? '<div class="vacante-info-item"><strong>Reclutadora:</strong> <span style="color:' + rec.color + ';font-weight:600;">' + escapeHtml(rec.nombreCorto) + '</span></div>' : ''; })()}
          </div>
        </div>
        <div class="vacante-card-actions" style="display:flex;gap:8px;flex-shrink:0;">
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleVacante(${v.id})">Ver Detalle</button>
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verCandidatosVacante(${v.id})">Candidatos</button>
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();cerrarVacante(${v.id})">${v.estado === 'abierta' ? 'Cerrar' : 'Abrir'}</button>
        </div>
      </div>`;
    }

    return `
      <div class="vacante-card" onclick="verDetalleVacante(${v.id})" style="cursor:pointer">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
          <div>
            <span style="font-size:11px;font-weight:700;color:var(--primary);letter-spacing:.5px;">${escapeHtml(v.codigo || '')}</span>
            <h3 class="vacante-title">${escapeHtml(v.titulo)}</h3>
          </div>
          <span class="vacante-status status-${v.estado}">${v.estado === 'abierta' ? 'ABIERTA' : 'CERRADA'}</span>
        </div>
        <div class="vacante-info">
          <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(v.departamento)}</div>
          <div class="vacante-info-item"><strong>Candidatos:</strong> ${candidatosCount}</div>
          <div class="vacante-info-item"><strong>Días publicada:</strong> ${diasPublicada} días</div>
          <div class="vacante-info-item"><strong>Publicada:</strong> ${formatFecha(v.fechaCreacion)}</div>
          ${(() => { var rec = v.reclutadoraId ? RECLUTADORAS.find(r => r.id === v.reclutadoraId) : null; return rec ? '<div class="vacante-info-item"><strong>Reclutadora:</strong> <span style="color:' + rec.color + ';font-weight:600;">' + escapeHtml(rec.nombreCorto) + '</span></div>' : ''; })()}
        </div>
        <p class="vacante-desc">${escapeHtml(v.descripcion).substring(0, 100)}...</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;">
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleVacante(${v.id})">Ver Detalle</button>
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verCandidatosVacante(${v.id})">Candidatos</button>
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();cerrarVacante(${v.id})">${v.estado === 'abierta' ? 'Cerrar' : 'Abrir'}</button>
        </div>
      </div>
    `;
  }).join('');
}

function populateVacanteFilters() {
  var sel = document.getElementById('vac-filter-reclutadora');
  if (sel) {
    sel.innerHTML = '<option value="">Todas</option>' +
      RECLUTADORAS.map(function(r) {
        return '<option value="' + r.id + '">' + escapeHtml(r.nombreCorto) + '</option>';
      }).join('');
  }
}

function aplicarVacante(vacanteId) {
  document.getElementById('aplicar-vacante-id').value = vacanteId;
  const vacante = vacantes.find(v => v.id === vacanteId);
  const nombreEl = document.getElementById('aplicar-vacante-nombre');
  if (nombreEl && vacante) nombreEl.textContent = vacante.titulo;

  var cvSeccion = document.getElementById('apli-cv-seccion');
  var cvInput = document.getElementById('apli-cv');
  var cvLabel = document.getElementById('apli-cv-label');
  var cvHelp = document.getElementById('apli-cv-help');
  if (vacante && vacante.solicitarCV) {
    if (cvSeccion) cvSeccion.style.display = '';
    if (cvInput) cvInput.required = true;
    if (cvLabel) cvLabel.textContent = 'Adjuntar CV *';
    if (cvHelp) cvHelp.textContent = 'Obligatorio. Formatos: PDF, DOC, DOCX. Máximo 2MB.';
  } else {
    if (cvSeccion) cvSeccion.style.display = 'none';
    if (cvInput) { cvInput.required = false; cvInput.value = ''; }
    if (cvLabel) cvLabel.textContent = 'Adjuntar CV';
    if (cvHelp) cvHelp.textContent = 'Opcional. Formatos: PDF, DOC, DOCX. Máximo 2MB.';
  }

  openModal('aplicar');
}

function cerrarVacante(vacanteId) {
  const vacante = vacantes.find(v => v.id === vacanteId);
  if (vacante) {
    vacante.estado = vacante.estado === 'abierta' ? 'cerrada' : 'abierta';
    saveData();
    renderVacantesGestion();
    if (document.getElementById('vacantesGrid')) renderVacantesPortal();
    showToast('Vacante actualizada', `La vacante ha sido ${vacante.estado === 'cerrada' ? 'cerrada' : 'abierta'}`);
  }
}

function verDetalleVacante(vacanteId) {
  const vacante = vacantes.find(v => v.id === vacanteId);
  if (!vacante) return;

  const candidatosVacante = candidatos.filter(c => c.vacanteId === vacanteId);
  const diasPublicada = Math.floor((new Date() - new Date(vacante.fechaCreacion)) / (1000 * 60 * 60 * 24));

  const porEtapa = {
    'aplicado': 0, 'entrevista-rh': 0, 'primer-filtro': 0,
    'entrevista-jefe': 0, 'revision-medica': 0, 'psicometrico': 0,
    'referencias': 0, 'documentos': 0, 'contratado': 0, 'rechazado': 0
  };

  candidatosVacante.forEach(c => {
    if (porEtapa.hasOwnProperty(c.etapa)) {
      porEtapa[c.etapa]++;
    }
  });

  const content = `
    <div class="card">
      <div class="card-header">
        <h2>Informaci\u00f3n de la Vacante</h2>
      </div>
      <div class="grid">
        <div class="col-6">
          ${vacante.codigo ? `<p><strong>Clave de Vacante:</strong> <span style="color:var(--primary);font-weight:700;letter-spacing:.5px;">${escapeHtml(vacante.codigo)}</span></p>` : ''}
          <p><strong>T\u00edtulo:</strong> ${escapeHtml(vacante.titulo)}</p>
          <p><strong>Departamento:</strong> ${escapeHtml(vacante.departamento)}</p>
          <p><strong>Ubicaci\u00f3n:</strong> ${escapeHtml(vacante.ubicacionClave ? (UBICACION_NOMBRES[vacante.ubicacionClave] || vacante.ubicacionClave) : vacante.ubicacion)}</p>
          ${vacante.direccion ? `<p><strong>Direcci\u00f3n:</strong> ${escapeHtml(vacante.direccion)}</p>` : ''}
          <p><strong>Tipo:</strong> ${escapeHtml(vacante.tipo)}</p>
        </div>
        <div class="col-6">
          <p><strong>Estado:</strong> <span class="badge status-${vacante.estado}">${vacante.estado}</span></p>
          <p><strong>Publicada:</strong> ${formatFecha(vacante.fechaCreacion)}</p>
          <p><strong>D\u00edas publicada:</strong> ${diasPublicada} d\u00edas</p>
          ${vacante.salario ? `<p><strong>Salario:</strong> ${escapeHtml(vacante.salario)}</p>` : ''}
        </div>
        <div class="col-12">
          <p><strong>Descripci\u00f3n:</strong></p>
          <p>${escapeHtml(vacante.descripcion)}</p>
        </div>
        ${vacante.requisitos ? `
        <div class="col-12">
          <p><strong>Requisitos:</strong></p>
          <p>${escapeHtml(vacante.requisitos)}</p>
        </div>
        ` : ''}
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Estad\u00edsticas de Candidatos</h2>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:20px;">
        <div class="mini-card">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Total</div>
          <div style="font-size:28px;font-weight:900;color:var(--primary);">${candidatosVacante.length}</div>
        </div>
        <div class="mini-card">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Postulados</div>
          <div style="font-size:28px;font-weight:900;color:#3b82f6;">${porEtapa.aplicado}</div>
        </div>
        <div class="mini-card">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">En Proceso</div>
          <div style="font-size:28px;font-weight:900;color:#f59e0b;">${
            porEtapa['entrevista-rh'] + porEtapa['primer-filtro'] + porEtapa['entrevista-jefe'] +
            porEtapa['revision-medica'] + porEtapa['psicometrico'] + porEtapa['referencias'] + porEtapa['documentos']
          }</div>
        </div>
        <div class="mini-card">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Contratados</div>
          <div style="font-size:28px;font-weight:900;color:#10b981;">${porEtapa.contratado}</div>
        </div>
        <div class="mini-card">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Rechazados</div>
          <div style="font-size:28px;font-weight:900;color:#dc2626;">${porEtapa.rechazado}</div>
        </div>
      </div>

      <div style="margin-top:20px;">
        <strong>Candidatos por Etapa:</strong>
        <div style="margin-top:12px;display:grid;gap:8px;">
          ${Object.entries(porEtapa).filter(([etapa, count]) => count > 0).map(([etapa, count]) => `
            <div style="display:flex;justify-content:space-between;padding:8px 12px;border:1px solid var(--border);border-radius:6px;background:#ffffff;">
              <span>${getEtapaLabel(etapa)}</span>
              <strong>${count}</strong>
            </div>
          `).join('')}
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Candidatos de esta Vacante</h2>
      </div>
      ${candidatosVacante.length > 0 ? `
        <table style="width:100%;border-collapse:collapse;display:table;">
          <thead style="background:#f9fafb;border-bottom:1px solid var(--border);display:table-header-group;">
            <tr style="display:table-row;">
              <th style="display:table-cell;padding:12px 16px;text-align:left;font-size:11px;letter-spacing:.7px;text-transform:uppercase;color:rgba(15,23,42,.60);font-weight:800;background:transparent;">Nombre</th>
              <th style="display:table-cell;padding:12px 16px;text-align:left;font-size:11px;letter-spacing:.7px;text-transform:uppercase;color:rgba(15,23,42,.60);font-weight:800;background:transparent;">Etapa</th>
              <th style="display:table-cell;padding:12px 16px;text-align:left;font-size:11px;letter-spacing:.7px;text-transform:uppercase;color:rgba(15,23,42,.60);font-weight:800;background:transparent;">Fecha Aplicaci\u00f3n</th>
              <th style="display:table-cell;padding:12px 16px;text-align:left;font-size:11px;letter-spacing:.7px;text-transform:uppercase;color:rgba(15,23,42,.60);font-weight:800;background:transparent;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            ${candidatosVacante.map(c => `
              <tr style="display:table-row;border-bottom:1px solid var(--border);background:transparent;">
                <td style="display:table-cell;padding:12px 16px;font-size:14px;color:var(--text);"><strong>${escapeHtml(c.nombre)} ${escapeHtml(c.apellidos)}</strong></td>
                <td style="display:table-cell;padding:12px 16px;font-size:14px;"><span class="badge badge-${getBadgeClass(c.etapa)}">${getEtapaLabel(c.etapa)}</span></td>
                <td style="display:table-cell;padding:12px 16px;font-size:14px;color:var(--text);">${formatFecha(c.fechaAplicacion)}</td>
                <td style="display:table-cell;padding:12px 16px;font-size:14px;">
                  <button class="btn btn-ghost btn-small" onclick="verDetalleCandidato(${c.id});closeModal('detalle-vacante')">Ver</button>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      ` : '<p style="padding:20px;text-align:center;color:var(--muted);">No hay candidatos para esta vacante</p>'}
    </div>
  `;

  document.getElementById('detalle-vacante-content').innerHTML = content;
  openModal('detalle-vacante');
}


// ==================== CANDIDATOS ====================
function renderCandidatosTable() {
  filtrarCandidatos();
}

function filtrarCandidatosPorDepto() {
  // Al cambiar departamento, repoblar vacantes filtradas y luego filtrar candidatos
  const depto = document.getElementById('cand-filter-departamento')?.value || '';
  const selectVacante = document.getElementById('cand-filter-vacante');
  if (selectVacante) {
    const vacsFiltradas = depto ? vacantes.filter(v => v.departamento === depto) : vacantes;
    selectVacante.innerHTML = '<option value="">Todas las vacantes</option>' +
      vacsFiltradas.map(v => `<option value="${v.id}">${escapeHtml(v.titulo)}</option>`).join('');
  }
  filtrarCandidatos();
}

function filtrarCandidatos() {
  const searchTerm = document.getElementById('cand-search')?.value.toLowerCase() || '';
  const filterDepto = document.getElementById('cand-filter-departamento')?.value || '';
  const filterVacante = document.getElementById('cand-filter-vacante')?.value || '';
  const filterEtapa = document.getElementById('cand-filter-etapa')?.value || '';

  // IDs de vacantes del departamento seleccionado
  const vacantesDeptoIds = filterDepto
    ? new Set(vacantes.filter(v => v.departamento === filterDepto).map(v => v.id))
    : null;

  let candidatosFiltrados = candidatos.filter(c => {
    const nombreCompleto = `${c.nombre} ${c.apellidos}`.toLowerCase();
    const matchSearch = nombreCompleto.includes(searchTerm);
    const matchDepto = !vacantesDeptoIds || vacantesDeptoIds.has(c.vacanteId);
    const matchVacante = !filterVacante || c.vacanteId === parseInt(filterVacante);
    let matchEtapa;
    if (!filterEtapa) {
      matchEtapa = true;
    } else if (filterEtapa === 'en-proceso') {
      matchEtapa = !['contratado', 'rechazado'].includes(c.etapa);
    } else {
      matchEtapa = c.etapa === filterEtapa;
    }

    return matchSearch && matchDepto && matchVacante && matchEtapa;
  });

  const container = document.getElementById('candidatosTable');
  if (!container) return;

  if (candidatosFiltrados.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <h3>No se encontraron candidatos</h3>
        <p>Intenta ajustar los filtros de b\u00fasqueda</p>
      </div>
    `;
    return;
  }

  container.innerHTML = `
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Vacante</th>
          <th>Experiencia</th>
          <th>Fecha de Aplicaci\u00f3n</th>
          <th>Etapa Actual</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        ${candidatosFiltrados.map(c => {
          const vacante = vacantes.find(v => v.id === c.vacanteId);
          return `
            <tr>
              <td><strong>${escapeHtml(c.nombre)} ${escapeHtml(c.apellidos)}</strong></td>
              <td>${vacante ? escapeHtml(vacante.titulo) : 'No disponible'}</td>
              <td>${escapeHtml(c.experiencia)}</td>
              <td>${formatFecha(c.fechaAplicacion)}</td>
              <td><span class="badge badge-${getBadgeClass(c.etapa)}">${getEtapaLabel(c.etapa)}</span></td>
              <td>
                <button class="btn btn-ghost btn-small" onclick="verDetalleCandidato(${c.id})">Ver Detalle</button>
              </td>
            </tr>
          `;
        }).join('')}
      </tbody>
    </table>
  `;
}

function populateCandidatoFilters() {
  const selectVacante = document.getElementById('cand-filter-vacante');
  if (selectVacante) {
    selectVacante.innerHTML = '<option value="">Todas las vacantes</option>' +
      vacantes.map(v => `<option value="${v.id}">${escapeHtml(v.titulo)}</option>`).join('');
  }
}

function getBadgeClass(etapa) {
  const classes = {
    'aplicado': 'aplicado', 'entrevista-rh': 'entrevista-rh',
    'primer-filtro': 'primer-filtro', 'entrevista-jefe': 'entrevista-jefe',
    'revision-medica': 'revision-medica', 'psicometrico': 'psicometrico',
    'referencias': 'referencias', 'documentos': 'documentos',
    'contratado': 'contratado', 'rechazado': 'rechazado'
  };
  return classes[etapa] || 'aplicado';
}

function getEtapaLabel(etapa) {
  const labels = {
    'aplicado': 'Postulado', 'entrevista-rh': 'Entrevista RH',
    'primer-filtro': 'Primer Filtro', 'entrevista-jefe': 'Entrevista Jefe',
    'revision-medica': 'Revisi\u00f3n M\u00e9dica', 'psicometrico': 'Psicom\u00e9trico',
    'referencias': 'Referencias', 'documentos': 'Documentos',
    'contratado': 'Contratado', 'rechazado': 'Rechazado'
  };
  return labels[etapa] || 'Postulado';
}

function getEtapaNumero(etapa) {
  const numeros = {
    'aplicado': 1, 'entrevista-rh': 2, 'primer-filtro': 3,
    'entrevista-jefe': 4, 'revision-medica': 5, 'psicometrico': 6,
    'referencias': 7, 'documentos': 8, 'contratado': 8, 'rechazado': 0
  };
  return numeros[etapa] || 1;
}

function getEtapaNombre(num) {
  const nombres = {
    1: 'aplicado', 2: 'entrevista-rh', 3: 'primer-filtro',
    4: 'entrevista-jefe', 5: 'revision-medica', 6: 'psicometrico',
    7: 'referencias', 8: 'documentos'
  };
  return nombres[num] || 'aplicado';
}

function verDetalleCandidato(candidatoId) {
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (!candidato) return;

  const vacante = vacantes.find(v => v.id === candidato.vacanteId);
  const etapaActual = getEtapaNumero(candidato.etapa);

  const content = document.getElementById('detalle-candidato-content');
  content.innerHTML = `
    <div class="card">
      <div class="card-header">
        <h2>Informaci\u00f3n Personal</h2>
      </div>
      <div class="grid">
        <div class="col-6">
          <p><strong>Nombre:</strong> ${escapeHtml(candidato.nombre)} ${escapeHtml(candidato.apellidos)}</p>
          <p><strong>Email:</strong> ${escapeHtml(candidato.email)}</p>
          <p><strong>Tel\u00e9fono:</strong> ${escapeHtml(candidato.telefono)}</p>
        </div>
        <div class="col-6">
          <p><strong>Ciudad:</strong> ${escapeHtml(candidato.ciudad)}</p>
          <p><strong>Fecha de Nacimiento:</strong> ${formatFecha(candidato.fechaNacimiento)}</p>
          <p><strong>Vacante:</strong> ${vacante ? escapeHtml(vacante.titulo) : 'No disponible'}</p>
          ${candidato.codigoSeguimiento ? `<p><strong>Código de Seguimiento:</strong> <span style="font-family:monospace;font-size:16px;font-weight:800;color:var(--primary);letter-spacing:2px;">${escapeHtml(candidato.codigoSeguimiento)}</span></p>` : ''}
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Experiencia y Educaci\u00f3n</h2>
      </div>
      <p><strong>Experiencia:</strong> ${escapeHtml(candidato.experiencia)}</p>
      <p><strong>\u00daltima Empresa:</strong> ${escapeHtml(candidato.ultimaEmpresa || candidato.ultimoEmpleo || 'No especificado')}</p>
      <p><strong>\u00daltimo Puesto:</strong> ${escapeHtml(candidato.ultimoPuesto || 'No especificado')}</p>
      <p><strong>Escolaridad:</strong> ${escapeHtml(candidato.escolaridad)}</p>
      <p><strong>Carrera:</strong> ${escapeHtml(candidato.carrera || 'No especificado')}</p>
      ${candidato.habilidades ? `<p><strong>Habilidades:</strong> ${escapeHtml(candidato.habilidades)}</p>` : ''}
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Curriculum Vitae</h2>
      </div>
      ${candidato.curriculum ? `
        <p><strong>Archivo:</strong> ${escapeHtml(candidato.curriculum.nombre)}</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;">
          <a class="btn btn-primary btn-small" href="${candidato.curriculum.data}" download="${escapeHtml(candidato.curriculum.nombre)}" style="text-decoration:none;">Descargar CV</a>
          ${candidato.curriculum.tipo === 'application/pdf' ? `<button class="btn btn-ghost btn-small" onclick="verCVEnLinea(${candidato.id})">Ver PDF</button>` : ''}
        </div>
      ` : `
        <p style="text-align:center;color:var(--muted);font-size:13px;padding:12px 0;">Sin CV adjunto</p>
      `}
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Proceso de Selecci\u00f3n</h2>
      </div>

      <div class="proceso-steps">
        ${renderProcesoSteps(etapaActual, candidato)}
      </div>

      ${candidato.etapa === 'rechazado' ? `
      <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;margin-top:12px;">
        <p style="margin:0 0 4px;font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:#991b1b;font-weight:800;">Candidato Rechazado ${candidato.etapaRechazo ? 'en ' + getEtapaLabel(getEtapaNombre(candidato.etapaRechazo)) : ''}</p>
        <p style="margin:0;font-size:14px;color:#7f1d1d;">${escapeHtml(candidato.motivoRechazo || 'Sin motivo registrado')}</p>
      </div>
      ` : ''}

      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:20px;">
        ${etapaActual === 1 ? `<button class="btn btn-primary" onclick="avanzarEtapa(${candidato.id}, 'entrevista-rh')">Aprobar Primer Filtro</button>` : ''}
        ${etapaActual === 2 ? (entrevistas.find(e => e.candidatoId === candidato.id && e.tipo === 'rh')
          ? `<button class="btn btn-primary" onclick="avanzarEtapa(${candidato.id}, 'primer-filtro')">Aprobar Entrevista RH</button>`
          : `<button class="btn btn-primary" onclick="agendarEntrevistaRH(${candidato.id})">Agendar Entrevista RH</button>`) : ''}
        ${etapaActual === 3 ? `<button class="btn btn-primary" onclick="agendarEntrevistaJefe(${candidato.id})">Agendar Entrevista con Jefe</button>` : ''}
        ${etapaActual === 4 ? `<button class="btn btn-primary" onclick="avanzarEtapa(${candidato.id}, 'revision-medica')">Pasar a Revisi\u00f3n M\u00e9dica</button>` : ''}
        ${etapaActual === 5 ? `<button class="btn btn-primary" onclick="avanzarEtapa(${candidato.id}, 'psicometrico')">Pasar a Pruebas Psicom\u00e9tricas</button>` : ''}
        ${etapaActual === 6 ? `<button class="btn btn-primary" onclick="avanzarEtapa(${candidato.id}, 'referencias')">Verificar Referencias</button>` : ''}
        ${etapaActual === 7 ? `<button class="btn btn-primary" onclick="avanzarEtapa(${candidato.id}, 'documentos')">Solicitar Documentos</button>` : ''}
        ${etapaActual === 8 && candidato.etapa === 'documentos' ? `<button class="btn btn-primary" onclick="iniciarAltaEmpleado(${candidato.id})">Contratar</button>` : ''}
        ${etapaActual > 0 && candidato.etapa !== 'contratado' && candidato.etapa !== 'rechazado' ? `<button class="btn btn-danger" onclick="rechazarCandidato(${candidato.id})">Rechazar</button>` : ''}
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Comentarios Públicos</h2>
      </div>
      <p style="font-size:12px;color:var(--muted);margin-bottom:12px;">Estos comentarios son visibles para el candidato cuando consulta su postulación.</p>
      <div style="display:flex;gap:8px;margin-bottom:16px;">
        <textarea id="comentario-publico-texto" placeholder="Escribe un comentario para el candidato..." style="flex:1;min-height:60px;"></textarea>
        <button class="btn btn-primary btn-small" onclick="agregarComentarioPublico(${candidato.id})" style="align-self:flex-end;">Agregar</button>
      </div>
      <div id="lista-comentarios-publicos">
        ${(candidato.comentariosPublicos || []).length === 0
          ? '<p style="text-align:center;color:var(--muted);font-size:13px;padding:12px 0;">Sin comentarios públicos aún</p>'
          : (candidato.comentariosPublicos || []).slice().reverse().map(c => `
            <div style="border:1px solid var(--border);border-radius:8px;padding:12px 14px;margin-bottom:8px;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);font-weight:700;">${escapeHtml(c.etapa || '')}</span>
                <span style="font-size:11px;color:var(--muted);">${formatFecha(c.fecha)}</span>
              </div>
              <p style="margin:0;font-size:14px;">${escapeHtml(c.texto)}</p>
            </div>
          `).join('')
        }
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h2>Comentarios del Jefe de Área</h2></div>
      <div>
        ${(candidato.comentariosInternos || []).length === 0
          ? '<p style="text-align:center;color:var(--muted);font-size:13px;padding:12px 0;">Sin comentarios del jefe de área</p>'
          : (candidato.comentariosInternos || []).slice().reverse().map(c => `
            <div style="border:1px solid var(--border);border-radius:8px;padding:12px 14px;margin-bottom:8px;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);font-weight:700;">Jefe de ${escapeHtml(c.autor || '')}</span>
                <span style="font-size:11px;color:var(--muted);">${formatFecha(c.fecha)}</span>
              </div>
              <p style="margin:0;font-size:14px;">${escapeHtml(c.texto)}</p>
            </div>
          `).join('')
        }
      </div>
    </div>
  `;

  openModal('detalle-candidato');
}

function verCVEnLinea(candidatoId) {
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (!candidato || !candidato.curriculum) return;

  const win = window.open('', '_blank');
  if (!win) {
    showToast('Ventana bloqueada', 'Permite las ventanas emergentes para ver el PDF.');
    return;
  }

  win.document.write(`
    <!doctype html>
    <html>
    <head><title>CV - ${escapeHtml(candidato.nombre)} ${escapeHtml(candidato.apellidos)}</title></head>
    <body style="margin:0;">
      <iframe src="${candidato.curriculum.data}" style="width:100%;height:100vh;border:none;"></iframe>
    </body>
    </html>
  `);
  win.document.close();
}

function agregarComentarioPublico(candidatoId) {
  const textarea = document.getElementById('comentario-publico-texto');
  if (!textarea) return;
  const texto = textarea.value.trim();
  if (!texto) {
    showToast('Comentario vacío', 'Escribe un comentario antes de agregar');
    return;
  }
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (!candidato) return;
  if (!candidato.comentariosPublicos) candidato.comentariosPublicos = [];
  candidato.comentariosPublicos.push({
    fecha: new Date().toISOString().split('T')[0],
    texto: texto,
    etapa: getEtapaLabel(candidato.etapa),
    autor: 'RH'
  });
  saveData();
  showToast('Comentario agregado', 'El candidato podrá verlo en su seguimiento');
  verDetalleCandidato(candidatoId);
}

// --- Detalle candidato (vista Jefe de Área - solo lectura) ---
function verDetalleCandidatoJefe(candidatoId) {
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (!candidato) return;

  const vacante = vacantes.find(v => v.id === candidato.vacanteId);
  const etapaActual = getEtapaNumero(candidato.etapa);

  const comentarios = candidato.comentariosInternos || [];

  const content = document.getElementById('detalle-candidato-content');
  content.innerHTML = `
    <div class="card">
      <div class="card-header"><h2>Información Personal</h2></div>
      <div class="grid">
        <div class="col-6">
          <p><strong>Nombre:</strong> ${escapeHtml(candidato.nombre)} ${escapeHtml(candidato.apellidos)}</p>
          <p><strong>Email:</strong> ${escapeHtml(candidato.email)}</p>
          <p><strong>Teléfono:</strong> ${escapeHtml(candidato.telefono)}</p>
        </div>
        <div class="col-6">
          <p><strong>Ciudad:</strong> ${escapeHtml(candidato.ciudad)}</p>
          <p><strong>Fecha de Nacimiento:</strong> ${formatFecha(candidato.fechaNacimiento)}</p>
          <p><strong>Vacante:</strong> ${vacante ? escapeHtml(vacante.titulo) : 'No disponible'}</p>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h2>Experiencia y Educación</h2></div>
      <p><strong>Experiencia:</strong> ${escapeHtml(candidato.experiencia)}</p>
      <p><strong>Última Empresa:</strong> ${escapeHtml(candidato.ultimaEmpresa || candidato.ultimoEmpleo || 'No especificado')}</p>
      <p><strong>Último Puesto:</strong> ${escapeHtml(candidato.ultimoPuesto || 'No especificado')}</p>
      <p><strong>Escolaridad:</strong> ${escapeHtml(candidato.escolaridad)}</p>
      <p><strong>Carrera:</strong> ${escapeHtml(candidato.carrera || 'No especificado')}</p>
      ${candidato.habilidades ? `<p><strong>Habilidades:</strong> ${escapeHtml(candidato.habilidades)}</p>` : ''}
    </div>

    <div class="card">
      <div class="card-header"><h2>Curriculum Vitae</h2></div>
      ${candidato.curriculum ? `
        <p><strong>Archivo:</strong> ${escapeHtml(candidato.curriculum.nombre)}</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;">
          <a class="btn btn-primary btn-small" href="${candidato.curriculum.data}" download="${escapeHtml(candidato.curriculum.nombre)}" style="text-decoration:none;">Descargar CV</a>
          ${candidato.curriculum.tipo === 'application/pdf' ? `<button class="btn btn-ghost btn-small" onclick="verCVEnLinea(${candidato.id})">Ver PDF</button>` : ''}
        </div>
      ` : `
        <p style="text-align:center;color:var(--muted);font-size:13px;padding:12px 0;">Sin CV adjunto</p>
      `}
    </div>

    <div class="card">
      <div class="card-header"><h2>Proceso de Selección</h2></div>
      <div class="proceso-steps">
        ${renderProcesoSteps(etapaActual, candidato)}
      </div>
      ${candidato.etapa === 'rechazado' ? `
      <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;margin-top:12px;">
        <p style="margin:0 0 4px;font-size:11px;text-transform:uppercase;letter-spacing:.7px;color:#991b1b;font-weight:800;">Candidato Rechazado ${candidato.etapaRechazo ? 'en ' + getEtapaLabel(getEtapaNombre(candidato.etapaRechazo)) : ''}</p>
        <p style="margin:0;font-size:14px;color:#7f1d1d;">${escapeHtml(candidato.motivoRechazo || 'Sin motivo registrado')}</p>
      </div>
      ` : ''}
    </div>

    <div class="card">
      <div class="card-header"><h2>Comentarios Internos</h2></div>
      <p style="font-size:12px;color:var(--muted);margin-bottom:12px;">Estos comentarios son visibles para RH.</p>
      <div style="display:flex;gap:8px;margin-bottom:16px;">
        <textarea id="comentario-interno-texto" placeholder="Escribe un comentario interno sobre este candidato..." style="flex:1;min-height:60px;"></textarea>
        <button class="btn btn-primary btn-small" onclick="agregarComentarioInterno(${candidato.id})" style="align-self:flex-end;">Agregar</button>
      </div>
      <div id="lista-comentarios-internos">
        ${comentarios.length === 0
          ? '<p style="text-align:center;color:var(--muted);font-size:13px;padding:12px 0;">Sin comentarios internos aún</p>'
          : comentarios.slice().reverse().map(c => `
            <div style="border:1px solid var(--border);border-radius:8px;padding:12px 14px;margin-bottom:8px;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                <span style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);font-weight:700;">Jefe de ${escapeHtml(c.autor)}</span>
                <span style="font-size:11px;color:var(--muted);">${formatFecha(c.fecha)}</span>
              </div>
              <p style="margin:0;font-size:14px;">${escapeHtml(c.texto)}</p>
            </div>
          `).join('')
        }
      </div>
    </div>
  `;

  openModal('detalle-candidato');
}

function agregarComentarioInterno(candidatoId) {
  const textarea = document.getElementById('comentario-interno-texto');
  if (!textarea) return;
  const texto = textarea.value.trim();
  if (!texto) {
    showToast('Comentario vacío', 'Escribe un comentario antes de agregar');
    return;
  }
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (!candidato) return;
  if (!candidato.comentariosInternos) candidato.comentariosInternos = [];
  candidato.comentariosInternos.push({
    fecha: new Date().toISOString().split('T')[0],
    texto: texto,
    autor: getDepartamentoJefe(),
    rolAutor: rolActual
  });
  saveData();

  crearNotificacion('rh', `Comentario sobre candidato: ${candidato.nombre} ${candidato.apellidos}`, { vista: 'gestion-candidatos', candidatoId: candidato.id });

  showToast('Comentario agregado', 'RH recibirá una notificación sobre tu comentario');
  verDetalleCandidatoJefe(candidatoId);
}

function renderProcesoSteps(etapaActual, candidato) {
  const steps = [
    { num: 1, label: 'Postulaci\u00f3n' },
    { num: 2, label: 'Entrevista RH' },
    { num: 3, label: 'Primer Filtro' },
    { num: 4, label: 'Entrevista Jefe' },
    { num: 5, label: 'Revisi\u00f3n M\u00e9dica' },
    { num: 6, label: 'Psicom\u00e9trico' },
    { num: 7, label: 'Referencias' },
    { num: 8, label: 'Documentos' }
  ];

  const esRechazado = candidato && candidato.etapa === 'rechazado';
  const etapaRechazo = esRechazado ? (candidato.etapaRechazo || 1) : null;

  const tieneEntrevistaRH = candidato && entrevistas.find(e => e.candidatoId === candidato.id && e.tipo === 'rh');

  return steps.map((step, index) => {
    const isRejected = esRechazado && step.num === etapaRechazo;
    const isCompleted = esRechazado ? step.num < etapaRechazo : step.num < etapaActual;
    const isActive = !esRechazado && step.num === etapaActual;
    const showConnector = index < steps.length - 1;
    const esEntrevistaRH = step.num === 2 && tieneEntrevistaRH && (isCompleted || isActive);
    const clickAttr = esEntrevistaRH ? `onclick="toggleDetalleEntrevista(${candidato.id})" style="cursor:pointer" title="Click para ver detalles de entrevista"` : '';

    return `
      <div class="step ${isActive ? 'active' : ''} ${isCompleted ? 'completed' : ''} ${isRejected ? 'rejected' : ''}" ${clickAttr}>
        <div class="step-number">${isRejected ? '\u2717' : step.num}</div>
        <div class="step-label">${step.label}</div>
      </div>
      ${showConnector ? `<div class="step-connector ${isCompleted ? 'completed' : ''} ${isRejected ? 'rejected' : ''}"></div>` : ''}
    `;
  }).join('');
}

function avanzarEtapa(candidatoId, nuevaEtapa) {
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (candidato) {
    candidato.etapa = nuevaEtapa;
    saveData();
    showToast('Etapa actualizada', `El candidato ha avanzado a: ${getEtapaLabel(nuevaEtapa)}`);
    closeModal('detalle-candidato');
    renderCandidatosTable();
  }
}

function toggleDetalleEntrevista(candidatoId) {
  var existing = document.getElementById('entrevista-detalle-popup');
  if (existing) {
    existing.remove();
    return;
  }

  var ent = entrevistas.find(function(e) { return e.candidatoId === candidatoId && e.tipo === 'rh'; });
  if (!ent) return;

  var fechaStr = formatFecha(ent.fecha);
  var horaInicio = ent.hora || '';
  var duracion = parseInt(ent.duracion) || 60;
  var horaFin = '';
  if (horaInicio) {
    var parts = horaInicio.split(':');
    var totalMin = parseInt(parts[0]) * 60 + parseInt(parts[1]) + duracion;
    horaFin = minutosAHora(totalMin);
  }

  var reclutadora = '';
  if (ent.reclutadoraId) {
    var rec = RECLUTADORAS.find(function(r) { return r.id === ent.reclutadoraId; });
    if (rec) reclutadora = rec.nombre;
  }

  var entrevistador = ent.entrevistador || '';
  var esOnline = ent.lugarClave === 'online' || ent.lugar === 'Reunión en línea';
  var lugarHTML = '';

  if (esOnline) {
    var link = escapeHtml(ent.linkReunion || '');
    lugarHTML = '<p style="margin:0 0 6px;font-size:13px;"><strong>Lugar:</strong> Reunión en línea</p>';
    if (ent.linkReunion) {
      lugarHTML += '<p style="margin:0 0 6px;font-size:13px;display:flex;align-items:center;gap:6px;">' +
        '<strong>Link:</strong> <a href="' + link + '" target="_blank" style="color:var(--primary);word-break:break-all;">' + link + '</a>' +
        ' <span onclick="copiarAlPortapapeles(\'' + link.replace(/'/g, "\\'") + '\')" style="cursor:pointer;font-size:16px;" title="Copiar enlace">📋</span></p>';
    }
  } else {
    var nombreUbicacion = UBICACION_NOMBRES[ent.lugarClave] || ent.lugar || '';
    var direccion = DIRECCIONES[ent.lugarClave] || ent.direccion || '';
    lugarHTML = '<p style="margin:0 0 6px;font-size:13px;"><strong>Lugar:</strong> ' + escapeHtml(nombreUbicacion) + '</p>';
    if (direccion) {
      var mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(direccion);
      lugarHTML += '<p style="margin:0 0 6px;font-size:13px;display:flex;align-items:center;gap:6px;">' +
        '<strong>Dirección:</strong> <a href="' + escapeHtml(mapsUrl) + '" target="_blank" style="color:var(--primary);word-break:break-all;">' + escapeHtml(direccion) + '</a>' +
        ' <span onclick="copiarAlPortapapeles(\'' + escapeHtml(direccion).replace(/'/g, "\\'") + '\')" style="cursor:pointer;font-size:16px;" title="Copiar dirección">📋</span></p>';
    }
  }

  var notasHTML = ent.notas ? '<p style="margin:0 0 6px;font-size:13px;"><strong>Notas:</strong> ' + escapeHtml(ent.notas) + '</p>' : '';

  var card = document.createElement('div');
  card.id = 'entrevista-detalle-popup';
  card.style.cssText = 'background:var(--bg-card,#fff);border:1px solid var(--border,#e5e7eb);border-radius:10px;padding:16px 18px;margin-top:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);';
  card.innerHTML =
    '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">' +
      '<h3 style="margin:0;font-size:15px;font-weight:700;">Detalles de Entrevista RH</h3>' +
      '<span onclick="document.getElementById(\'entrevista-detalle-popup\').remove()" style="cursor:pointer;font-size:18px;color:var(--muted);" title="Cerrar">&times;</span>' +
    '</div>' +
    '<p style="margin:0 0 6px;font-size:13px;"><strong>Fecha:</strong> ' + escapeHtml(fechaStr) + '</p>' +
    '<p style="margin:0 0 6px;font-size:13px;"><strong>Horario:</strong> ' + escapeHtml(horaInicio) + ' - ' + escapeHtml(horaFin) + '</p>' +
    '<p style="margin:0 0 6px;font-size:13px;"><strong>Duración:</strong> ' + duracion + ' min</p>' +
    (reclutadora ? '<p style="margin:0 0 6px;font-size:13px;"><strong>Reclutadora:</strong> ' + escapeHtml(reclutadora) + '</p>' : '') +
    (entrevistador ? '<p style="margin:0 0 6px;font-size:13px;"><strong>Entrevistador:</strong> ' + escapeHtml(entrevistador) + '</p>' : '') +
    lugarHTML +
    notasHTML;

  var stepsContainer = document.querySelector('.proceso-steps');
  if (stepsContainer) {
    stepsContainer.parentNode.insertBefore(card, stepsContainer.nextSibling);
  }
}

function copiarAlPortapapeles(texto) {
  navigator.clipboard.writeText(texto).then(function() {
    showToast('Copiado', 'Copiado al portapapeles');
  });
}

function rechazarCandidato(candidatoId) {
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (!candidato) return;

  document.getElementById('rechazo-candidato-id').value = candidatoId;
  document.getElementById('rechazo-etapa-info').textContent = getEtapaLabel(candidato.etapa);
  document.getElementById('rechazo-motivo').value = '';

  closeModal('detalle-candidato');
  openModal('rechazar-candidato');
}

function confirmarRechazo() {
  const candidatoId = parseInt(document.getElementById('rechazo-candidato-id').value);
  const motivo = document.getElementById('rechazo-motivo').value.trim();

  if (!motivo) {
    showToast('Campo obligatorio', 'Debes ingresar el motivo del rechazo');
    document.getElementById('rechazo-motivo').focus();
    return;
  }

  const candidato = candidatos.find(c => c.id === candidatoId);
  if (candidato) {
    candidato.etapaRechazo = getEtapaNumero(candidato.etapa);
    candidato.motivoRechazo = motivo;
    candidato.etapa = 'rechazado';
    saveData();
    showToast('Candidato rechazado', 'El candidato ha sido marcado como rechazado');
    closeModal('rechazar-candidato');
    renderCandidatosTable();
  }
}

function agendarEntrevistaRH(candidatoId) {
  document.getElementById('entrevista-candidato-id').value = candidatoId;
  document.getElementById('entrevista-tipo').value = 'rh';
  document.getElementById('entrevista-reclutadora-wrapper').style.display = '';
  document.getElementById('entrevista-reclutadora').required = true;
  var entInput = document.getElementById('entrevista-entrevistador');
  entInput.readOnly = true;
  entInput.style.background = '#f3f4f6';
  entInput.style.cursor = 'not-allowed';
  entInput.value = '';
  document.getElementById('entrevista-disponibilidad-wrapper').style.display = 'none';
  poblarSelectorReclutadora();
  miniCalSemanaOffset = 0;
  closeModal('detalle-candidato');
  openModal('agendar-entrevista');
}

function agendarEntrevistaJefe(candidatoId) {
  document.getElementById('entrevista-candidato-id').value = candidatoId;
  document.getElementById('entrevista-tipo').value = 'jefe';
  document.getElementById('entrevista-reclutadora-wrapper').style.display = 'none';
  document.getElementById('entrevista-reclutadora').required = false;
  document.getElementById('entrevista-reclutadora').value = '';
  var entInput = document.getElementById('entrevista-entrevistador');
  entInput.readOnly = false;
  entInput.style.background = '';
  entInput.style.cursor = '';
  entInput.value = '';
  document.getElementById('entrevista-disponibilidad-wrapper').style.display = 'none';
  closeModal('detalle-candidato');
  openModal('agendar-entrevista');
}

function cancelarAgendarEntrevista() {
  var candidatoId = parseInt(document.getElementById('entrevista-candidato-id').value);
  closeModal('agendar-entrevista');
  document.getElementById('form-agendar-entrevista').reset();
  document.getElementById('entrevista-disponibilidad-wrapper').style.display = 'none';
  if (candidatoId) {
    verDetalleCandidato(candidatoId);
  }
}

// ==================== RECLUTADORAS EN MODAL ENTREVISTA ====================

function poblarSelectorReclutadora() {
  var select = document.getElementById('entrevista-reclutadora');
  if (!select) return;
  select.innerHTML = '<option value="">Seleccionar reclutadora...</option>';
  RECLUTADORAS.forEach(function(r) {
    var opt = document.createElement('option');
    opt.value = r.id;
    opt.textContent = r.nombre;
    select.appendChild(opt);
  });
}

function onReclutadoraSeleccionada() {
  var recId = document.getElementById('entrevista-reclutadora').value;
  var rec = RECLUTADORAS.find(function(r) { return r.id === recId; });
  var entInput = document.getElementById('entrevista-entrevistador');
  var wrapper = document.getElementById('entrevista-disponibilidad-wrapper');
  if (rec) {
    entInput.value = rec.nombre;
    wrapper.style.display = '';
    renderMiniCalendarioDisponibilidad(recId);
  } else {
    entInput.value = '';
    wrapper.style.display = 'none';
  }
}

let miniCalSemanaOffset = 0;

function getMonday(date) {
  var d = new Date(date);
  var day = d.getDay();
  var diff = d.getDate() - day + (day === 0 ? -6 : 1);
  d.setDate(diff);
  d.setHours(0, 0, 0, 0);
  return d;
}

function miniCalNavSemana(dir) {
  miniCalSemanaOffset += dir;
  var recId = document.getElementById('entrevista-reclutadora').value;
  if (recId) renderMiniCalendarioDisponibilidad(recId);
}

function renderMiniCalendarioDisponibilidad(reclutadoraId) {
  var container = document.getElementById('entrevista-mini-calendario');
  if (!container) return;

  var hoy = new Date();
  var lunes = getMonday(hoy);
  lunes.setDate(lunes.getDate() + (miniCalSemanaOffset * 7));

  var viernes = new Date(lunes);
  viernes.setDate(lunes.getDate() + 4);

  var rec = RECLUTADORAS.find(function(r) { return r.id === reclutadoraId; });

  var html = '<div class="mini-cal-header">';
  html += '<button type="button" class="btn btn-ghost btn-small" onclick="miniCalNavSemana(-1)">&lsaquo;</button>';
  html += '<span class="mini-cal-titulo">' + lunes.getDate() + ' ' + MESES_ES[lunes.getMonth()] + ' - ' + viernes.getDate() + ' ' + MESES_ES[viernes.getMonth()] + ' ' + viernes.getFullYear() + '</span>';
  html += '<button type="button" class="btn btn-ghost btn-small" onclick="miniCalNavSemana(1)">&rsaquo;</button>';
  html += '</div>';

  html += '<div class="mini-cal-grid">';
  html += '<div class="mini-cal-hora-col"></div>';
  for (var d = 0; d < 5; d++) {
    var dia = new Date(lunes);
    dia.setDate(lunes.getDate() + d);
    html += '<div class="mini-cal-dia-header">' + CALENDARIO_CONFIG.diasSemana[d].substring(0, 3) + ' ' + dia.getDate() + '</div>';
  }

  for (var h = CALENDARIO_CONFIG.horaInicio; h < CALENDARIO_CONFIG.horaFin; h++) {
    for (var m = 0; m < 60; m += CALENDARIO_CONFIG.slotMinutos) {
      var horaStr = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
      html += '<div class="mini-cal-hora-label">' + (m === 0 ? horaStr : '') + '</div>';

      for (var d2 = 0; d2 < 5; d2++) {
        var dia2 = new Date(lunes);
        dia2.setDate(lunes.getDate() + d2);
        var fechaISO = dia2.toISOString().split('T')[0];
        var ocupado = isSlotOcupado(reclutadoraId, fechaISO, h, m);
        var claseSlot = ocupado ? 'mini-cal-ocupado' : 'mini-cal-libre';
        var titulo = ocupado ? 'Ocupado: ' + ocupado.candidatoNombre : 'Disponible - Click para seleccionar';

        html += '<div class="mini-cal-slot ' + claseSlot + '" data-fecha="' + fechaISO + '" data-hora="' + horaStr + '" onclick="' + (ocupado ? '' : "seleccionarSlotMiniCal('" + fechaISO + "','" + horaStr + "')") + '" title="' + escapeHtml(titulo) + '">';
        if (ocupado && ocupado.isStart) {
          var spanSlots = ocupado.duracion / CALENDARIO_CONFIG.slotMinutos;
          var spanPx = spanSlots * 25 - 1;
          html += '<span class="mini-cal-bloque" style="background:' + (rec ? rec.color : '#94a3b8') + ';color:#fff;position:absolute;top:0;left:1px;right:1px;height:' + spanPx + 'px;z-index:1;display:flex;align-items:center;">' + escapeHtml(ocupado.horaCorta) + '</span>';
        }
        html += '</div>';
      }
    }
  }
  html += '</div>';
  container.innerHTML = html;
}

function isSlotOcupado(reclutadoraId, fecha, hora, minuto) {
  var slotInicio = hora * 60 + minuto;
  var slotFin = slotInicio + CALENDARIO_CONFIG.slotMinutos;
  for (var i = 0; i < entrevistas.length; i++) {
    var ent = entrevistas[i];
    if (ent.reclutadoraId !== reclutadoraId) continue;
    if (ent.fecha !== fecha) continue;
    var partes = ent.hora.split(':');
    var entInicio = parseInt(partes[0]) * 60 + parseInt(partes[1]);
    var entFin = entInicio + parseInt(ent.duracion || 60);
    if (slotInicio < entFin && slotFin > entInicio) {
      var candidato = candidatos.find(function(c) { return c.id === ent.candidatoId; });
      return {
        horaCorta: ent.hora,
        candidatoNombre: candidato ? (candidato.nombre + ' ' + candidato.apellidos) : 'Entrevista',
        isStart: slotInicio === entInicio,
        duracion: entFin - entInicio
      };
    }
  }
  return null;
}

function seleccionarSlotMiniCal(fecha, hora) {
  document.getElementById('entrevista-fecha').value = fechaISOaDDMMAAAA(fecha);
  document.getElementById('entrevista-hora').value = hora;
  miniCalMostrarSeleccion(fecha, hora);
}

function miniCalMostrarSeleccion(fecha, hora) {
  // Limpiar selección previa
  var prev = document.getElementById('mini-cal-sel-block');
  if (prev) prev.remove();
  document.querySelectorAll('.mini-cal-slot.mini-cal-selected').forEach(function(el) {
    el.classList.remove('mini-cal-selected');
  });

  if (!fecha || !hora) return;

  var slot = document.querySelector('.mini-cal-slot[data-fecha="' + fecha + '"][data-hora="' + hora + '"]');
  if (!slot) return;

  slot.classList.add('mini-cal-selected');

  var duracion = parseInt(document.getElementById('entrevista-duracion').value) || 60;
  var spanSlots = duracion / CALENDARIO_CONFIG.slotMinutos;
  var spanPx = spanSlots * 25 - 1;

  var block = document.createElement('span');
  block.id = 'mini-cal-sel-block';
  block.className = 'mini-cal-sel-block';
  block.style.height = spanPx + 'px';
  block.textContent = hora + ' - ' + minutosAHora(parseInt(hora.split(':')[0]) * 60 + parseInt(hora.split(':')[1]) + duracion);
  slot.appendChild(block);
}

function onEntrevistaLugarChange() {
  var sel = document.getElementById('entrevista-lugar');
  var dirEl = document.getElementById('entrevista-lugar-direccion');
  var linkWrapper = document.getElementById('entrevista-link-wrapper');
  var linkInput = document.getElementById('entrevista-link');
  var val = sel.value;

  if (val === 'online') {
    dirEl.style.display = 'none';
    dirEl.textContent = '';
    linkWrapper.style.display = '';
    linkInput.required = true;
  } else if (val && DIRECCIONES[val]) {
    dirEl.style.display = 'block';
    dirEl.textContent = DIRECCIONES[val];
    linkWrapper.style.display = 'none';
    linkInput.required = false;
    linkInput.value = '';
  } else {
    dirEl.style.display = 'none';
    dirEl.textContent = '';
    linkWrapper.style.display = 'none';
    linkInput.required = false;
    linkInput.value = '';
  }
}

// ==================== VALIDACI\u00d3N DE CONFLICTOS ====================

function detectarConflictoHorario(reclutadoraId, fecha, hora, duracionMin, excluirId) {
  var partes = hora.split(':');
  var nuevaInicio = parseInt(partes[0]) * 60 + parseInt(partes[1]);
  var nuevaFin = nuevaInicio + (duracionMin || 60);
  for (var i = 0; i < entrevistas.length; i++) {
    var ent = entrevistas[i];
    if (excluirId && ent.id === excluirId) continue;
    if (ent.reclutadoraId !== reclutadoraId) continue;
    if (ent.fecha !== fecha) continue;
    var ep = ent.hora.split(':');
    var entInicio = parseInt(ep[0]) * 60 + parseInt(ep[1]);
    var entFin = entInicio + parseInt(ent.duracion || 60);
    if (nuevaInicio < entFin && nuevaFin > entInicio) {
      var rec = RECLUTADORAS.find(function(r) { return r.id === reclutadoraId; });
      return {
        reclutadoraNombre: rec ? rec.nombre : 'Reclutadora',
        horaInicio: ent.hora,
        horaFin: minutosAHora(entFin)
      };
    }
  }
  return null;
}

function minutosAHora(totalMinutos) {
  var h = Math.floor(totalMinutos / 60);
  var m = totalMinutos % 60;
  return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
}

// ==================== CALENDARIO DE RECLUTADORAS ====================

let calRecSemanaOffset = 0;
let calRecFiltroReclutadora = 'todas';
let calRecDragState = null;
let calRecDias = [];

function calRecNavSemana(dir) {
  calRecSemanaOffset += dir;
  renderCalendarioReclutadoras();
}

function calRecHoy() {
  calRecSemanaOffset = 0;
  renderCalendarioReclutadoras();
}

function renderCalendarioReclutadoras() {
  var lunes = getMonday(new Date());
  lunes.setDate(lunes.getDate() + (calRecSemanaOffset * 7));
  var viernes = new Date(lunes);
  viernes.setDate(lunes.getDate() + 4);

  // Titulo de semana
  var tituloEl = document.getElementById('cal-rec-semana-titulo');
  if (tituloEl) {
    tituloEl.textContent = lunes.getDate() + ' ' + MESES_ES[lunes.getMonth()] + ' - ' + viernes.getDate() + ' ' + MESES_ES[viernes.getMonth()] + ' ' + viernes.getFullYear();
  }

  renderCalRecTabs();
  renderCalRecGrid(lunes);
  renderVacantesAsignadasPorReclutadora();
}

function renderCalRecTabs() {
  var container = document.getElementById('cal-rec-tabs');
  if (!container) return;
  var html = '';
  html += '<button class="cal-rec-tab' + (calRecFiltroReclutadora === 'todas' ? ' active' : '') + '" onclick="calRecFiltrar(\'todas\')">';
  html += '<span class="cal-rec-dot" style="background:#94a3b8;"></span> Todas</button>';
  RECLUTADORAS.forEach(function(r) {
    var activa = calRecFiltroReclutadora === r.id;
    html += '<button class="cal-rec-tab' + (activa ? ' active' : '') + '" onclick="calRecFiltrar(\'' + r.id + '\')">';
    html += '<span class="cal-rec-dot" style="background:' + r.color + ';"></span> ' + escapeHtml(r.nombreCorto) + '</button>';
  });
  container.innerHTML = html;
}

function calRecFiltrar(filtro) {
  calRecFiltroReclutadora = filtro;
  renderCalendarioReclutadoras();
}

function renderCalRecGrid(lunes) {
  var container = document.getElementById('cal-rec-grid-container');
  if (!container) return;

  var hoy = new Date();
  hoy.setHours(0, 0, 0, 0);
  var hoyISO = hoy.toISOString().split('T')[0];

  var dias = [];
  for (var d = 0; d < 5; d++) {
    var dia = new Date(lunes);
    dia.setDate(lunes.getDate() + d);
    dias.push({ date: dia, iso: dia.toISOString().split('T')[0] });
  }
  calRecDias = dias;

  var totalSlots = (CALENDARIO_CONFIG.horaFin - CALENDARIO_CONFIG.horaInicio) * (60 / CALENDARIO_CONFIG.slotMinutos);
  var slotHeight = 40;
  var headerHeight = 42;
  var horaColWidth = 60;

  var html = '<div class="cal-rec-grid" style="grid-template-rows: auto repeat(' + totalSlots + ', ' + slotHeight + 'px);">';

  // Header row
  html += '<div class="cal-rec-header cal-rec-hora-col">Hora</div>';
  for (var d = 0; d < 5; d++) {
    var esHoy = dias[d].iso === hoyISO;
    html += '<div class="cal-rec-header cal-rec-dia-header' + (esHoy ? ' cal-rec-hoy' : '') + '">';
    html += CALENDARIO_CONFIG.diasSemana[d] + ' <span style="font-weight:400;">' + dias[d].date.getDate() + '</span>';
    html += '</div>';
  }

  // Time rows
  for (var slot = 0; slot < totalSlots; slot++) {
    var totalMin = CALENDARIO_CONFIG.horaInicio * 60 + slot * CALENDARIO_CONFIG.slotMinutos;
    var h = Math.floor(totalMin / 60);
    var m = totalMin % 60;
    var horaLabel = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
    html += '<div class="cal-rec-hora-label">' + (m === 0 ? horaLabel : '') + '</div>';
    for (var d2 = 0; d2 < 5; d2++) {
      var esHoy2 = dias[d2].iso === hoyISO;
      html += '<div class="cal-rec-celda' + (esHoy2 ? ' cal-rec-hoy-bg' : '') + (slot % 2 === 0 ? ' cal-rec-celda-par' : '') + '"></div>';
    }
  }
  html += '</div>';

  // Overlay de bloques de entrevistas (position absolute via CSS)
  html += '<div class="cal-rec-bloques-overlay">';

  var entrevistasSemana = entrevistas.filter(function(e) {
    var enSemana = dias.some(function(dia) { return dia.iso === e.fecha; });
    if (!enSemana) return false;
    if (calRecFiltroReclutadora !== 'todas' && e.reclutadoraId !== calRecFiltroReclutadora) return false;
    return true;
  });

  // Pre-procesar bloques con datos de posición y detectar solapamientos
  var bloquesPorDia = {};
  entrevistasSemana.forEach(function(ent) {
    var diaIdx = dias.findIndex(function(dia) { return dia.iso === ent.fecha; });
    if (diaIdx === -1) return;
    var partes = ent.hora.split(':');
    var inicioMin = parseInt(partes[0]) * 60 + parseInt(partes[1]);
    var finMin = inicioMin + parseInt(ent.duracion || 60);
    if (inicioMin - CALENDARIO_CONFIG.horaInicio * 60 < 0) return;
    if (!bloquesPorDia[diaIdx]) bloquesPorDia[diaIdx] = [];
    bloquesPorDia[diaIdx].push({ ent: ent, diaIdx: diaIdx, inicioMin: inicioMin, finMin: finMin, col: 0, totalCols: 1 });
  });

  // Para cada día, asignar columnas a bloques solapados
  Object.keys(bloquesPorDia).forEach(function(diaKey) {
    var bloques = bloquesPorDia[diaKey];
    bloques.sort(function(a, b) { return a.inicioMin - b.inicioMin || a.finMin - b.finMin; });

    // Agrupar bloques que se solapan entre sí (clusters)
    var clusters = [];
    bloques.forEach(function(blk) {
      var added = false;
      for (var ci = 0; ci < clusters.length; ci++) {
        var cluster = clusters[ci];
        var overlaps = cluster.some(function(cb) {
          return blk.inicioMin < cb.finMin && blk.finMin > cb.inicioMin;
        });
        if (overlaps) {
          cluster.push(blk);
          added = true;
          break;
        }
      }
      if (!added) clusters.push([blk]);
    });

    // Dentro de cada cluster, asignar columnas con algoritmo greedy
    clusters.forEach(function(cluster) {
      var columns = []; // columns[i] = finMin del último bloque en esa columna
      cluster.forEach(function(blk) {
        var placed = false;
        for (var c = 0; c < columns.length; c++) {
          if (blk.inicioMin >= columns[c]) {
            blk.col = c;
            columns[c] = blk.finMin;
            placed = true;
            break;
          }
        }
        if (!placed) {
          blk.col = columns.length;
          columns.push(blk.finMin);
        }
      });
      var totalCols = columns.length;
      cluster.forEach(function(blk) { blk.totalCols = totalCols; });
    });
  });

  // Renderizar todos los bloques con posición ajustada
  var allBloques = [];
  Object.keys(bloquesPorDia).forEach(function(k) {
    allBloques = allBloques.concat(bloquesPorDia[k]);
  });

  allBloques.forEach(function(blk) {
    var ent = blk.ent;
    var diaIdx = blk.diaIdx;
    var inicioOffset = blk.inicioMin - CALENDARIO_CONFIG.horaInicio * 60;
    var duracion = blk.finMin - blk.inicioMin;

    var top = headerHeight + (inicioOffset / CALENDARIO_CONFIG.slotMinutos) * slotHeight;
    var height = (duracion / CALENDARIO_CONFIG.slotMinutos) * slotHeight;

    var rec = RECLUTADORAS.find(function(r) { return r.id === ent.reclutadoraId; });
    var color = rec ? rec.color : '#94a3b8';
    var colorLight = rec ? rec.colorLight : '#f1f5f9';
    var recNombre = rec ? rec.nombreCorto : '';

    var candidato = candidatos.find(function(c) { return c.id === ent.candidatoId; });
    var candNombre = candidato ? (candidato.nombre + ' ' + candidato.apellidos) : 'Entrevista';
    var vacante = candidato ? vacantes.find(function(v) { return v.id === candidato.vacanteId; }) : null;
    var vacTitulo = vacante ? vacante.titulo : '';

    // Position: subdividir la columna del día según solapamientos
    var colFraction = blk.col + ' * (100% - ' + horaColWidth + 'px) / 5 / ' + blk.totalCols;
    var leftCalc = 'calc(' + horaColWidth + 'px + ' + diaIdx + ' * (100% - ' + horaColWidth + 'px) / 5 + ' + colFraction + ' + 2px)';
    var widthCalc = 'calc((100% - ' + horaColWidth + 'px) / 5 / ' + blk.totalCols + ' - 4px)';

    html += '<div class="cal-rec-bloque" data-entrevista-id="' + ent.id + '" data-candidato-id="' + (candidato ? candidato.id : '') + '" style="';
    html += 'position:absolute;';
    html += 'top:' + top + 'px;';
    html += 'left:' + leftCalc + ';';
    html += 'width:' + widthCalc + ';';
    html += 'height:' + (height - 2) + 'px;';
    html += 'background:' + colorLight + ';';
    html += 'border-left:3px solid ' + color + ';';
    html += '" title="' + escapeHtml(candNombre + ' - ' + vacTitulo) + '">';
    html += '<div class="cal-rec-bloque-hora" style="color:' + color + ';">' + ent.hora + ' - ' + minutosAHora(blk.finMin) + '</div>';
    html += '<div class="cal-rec-bloque-nombre">' + escapeHtml(candNombre) + '</div>';
    if (height > 45) {
      html += '<div class="cal-rec-bloque-info">' + escapeHtml(recNombre) + (vacTitulo ? ' &middot; ' + escapeHtml(vacTitulo) : '') + '</div>';
    }
    html += '</div>';
  });

  html += '</div>';

  container.innerHTML = html;
}

// ==================== DRAG & DROP CALENDARIO RECLUTADORAS ====================

function calRecPixelToSlot(clientX, clientY) {
  var container = document.getElementById('cal-rec-grid-container');
  if (!container) return null;
  var grid = container.querySelector('.cal-rec-grid');
  if (!grid) return null;

  var rect = grid.getBoundingClientRect();
  var horaColWidth = 60;
  var headerHeight = 42;
  var slotHeight = 40;

  var relX = clientX - rect.left - horaColWidth;
  var relY = clientY - rect.top - headerHeight;

  if (relX < 0 || relY < 0) return null;

  var dayWidth = (rect.width - horaColWidth) / 5;
  var dayIndex = Math.floor(relX / dayWidth);
  if (dayIndex < 0 || dayIndex > 4) return null;

  var totalSlots = (CALENDARIO_CONFIG.horaFin - CALENDARIO_CONFIG.horaInicio) * (60 / CALENDARIO_CONFIG.slotMinutos);
  var slotIndex = Math.floor(relY / slotHeight);
  if (slotIndex < 0 || slotIndex >= totalSlots) return null;

  // Snap a slots de 30 min
  var totalMinutes = CALENDARIO_CONFIG.horaInicio * 60 + slotIndex * CALENDARIO_CONFIG.slotMinutos;

  return { dayIndex: dayIndex, totalMinutes: totalMinutes };
}

function initCalRecDragAndDrop() {
  var container = document.getElementById('cal-rec-grid-container');
  if (!container) return;

  container.addEventListener('mousedown', function(e) {
    var bloque = e.target.closest('.cal-rec-bloque');
    if (!bloque) return;

    var entrevistaIdRaw = bloque.getAttribute('data-entrevista-id');
    var candidatoId = bloque.getAttribute('data-candidato-id');
    if (!entrevistaIdRaw) return;

    // Convertir a número para comparación === con los IDs numéricos
    var entrevistaId = isNaN(Number(entrevistaIdRaw)) ? entrevistaIdRaw : Number(entrevistaIdRaw);
    var candidatoIdNum = (candidatoId && !isNaN(Number(candidatoId))) ? Number(candidatoId) : candidatoId;
    var ent = entrevistas.find(function(en) { return en.id === entrevistaId; });
    if (!ent) return;

    e.preventDefault();

    calRecDragState = {
      entrevistaId: entrevistaId,
      candidatoId: candidatoIdNum,
      bloqueEl: bloque,
      startX: e.clientX,
      startY: e.clientY,
      origFecha: ent.fecha,
      origHora: ent.hora,
      isDragging: false,
      ghostEl: null
    };
  });

  document.addEventListener('mousemove', function(e) {
    if (!calRecDragState) return;

    var dx = e.clientX - calRecDragState.startX;
    var dy = e.clientY - calRecDragState.startY;

    // Umbral de 5px para distinguir click de drag
    if (!calRecDragState.isDragging) {
      if (Math.abs(dx) < 5 && Math.abs(dy) < 5) return;
      calRecDragState.isDragging = true;
      calRecDragState.bloqueEl.classList.add('cal-rec-bloque-dragging');
      document.body.style.userSelect = 'none';

      // Crear ghost con ancho completo de columna del día
      var ghost = document.createElement('div');
      ghost.className = 'cal-rec-bloque-ghost';
      var overlay = calRecDragState.bloqueEl.parentElement;
      ghost.style.height = calRecDragState.bloqueEl.style.height;
      ghost.style.width = 'calc((100% - 60px) / 5 - 4px)';
      ghost.style.top = calRecDragState.bloqueEl.style.top;
      ghost.style.left = calRecDragState.bloqueEl.style.left;
      overlay.appendChild(ghost);
      calRecDragState.ghostEl = ghost;
    }

    e.preventDefault();

    // Actualizar posición del ghost
    var slot = calRecPixelToSlot(e.clientX, e.clientY);
    if (!slot) {
      if (calRecDragState.ghostEl) calRecDragState.ghostEl.style.display = 'none';
      return;
    }

    // Clampear: la cita no debe exceder las 17:00
    var ent = entrevistas.find(function(en) { return en.id === calRecDragState.entrevistaId; });
    var duracion = ent ? parseInt(ent.duracion || 60) : 60;
    var maxInicio = CALENDARIO_CONFIG.horaFin * 60 - duracion;
    if (slot.totalMinutes > maxInicio) {
      slot.totalMinutes = maxInicio;
    }

    // Calcular posición visual del ghost
    var horaColWidth = 60;
    var headerHeight = 42;
    var slotHeight = 40;
    var inicioOffset = slot.totalMinutes - CALENDARIO_CONFIG.horaInicio * 60;
    var top = headerHeight + (inicioOffset / CALENDARIO_CONFIG.slotMinutos) * slotHeight;
    var leftCalc = 'calc(' + horaColWidth + 'px + ' + slot.dayIndex + ' * (100% - ' + horaColWidth + 'px) / 5 + 2px)';

    calRecDragState.ghostEl.style.display = '';
    calRecDragState.ghostEl.style.top = top + 'px';
    calRecDragState.ghostEl.style.left = leftCalc;
    calRecDragState.lastSlot = slot;
  });

  document.addEventListener('mouseup', function(e) {
    if (!calRecDragState) return;

    var state = calRecDragState;
    calRecDragState = null;

    // Restaurar selección de texto
    document.body.style.userSelect = '';

    // Limpiar ghost
    if (state.ghostEl) {
      state.ghostEl.remove();
    }

    // Si no fue drag (< 5px), ejecutar click original
    if (!state.isDragging) {
      if (state.candidatoId) {
        verDetalleCandidato(state.candidatoId);
      }
      return;
    }

    // Limpiar clase de dragging
    state.bloqueEl.classList.remove('cal-rec-bloque-dragging');

    // Sin target válido
    if (!state.lastSlot) return;

    // Calcular nueva fecha y hora
    var newDayIndex = state.lastSlot.dayIndex;
    var newTotalMin = state.lastSlot.totalMinutes;

    if (newDayIndex < 0 || newDayIndex > 4 || !calRecDias[newDayIndex]) return;

    var newFecha = calRecDias[newDayIndex].iso;
    var newHora = minutosAHora(newTotalMin);

    // Misma posición → no-op
    if (newFecha === state.origFecha && newHora === state.origHora) return;

    // Buscar la entrevista
    var ent = entrevistas.find(function(en) { return en.id === state.entrevistaId; });
    if (!ent) return;

    // Detectar conflicto
    var duracion = parseInt(ent.duracion || 60);
    var conflicto = detectarConflictoHorario(ent.reclutadoraId, newFecha, newHora, duracion, ent.id);
    if (conflicto) {
      showToast('Conflicto de horario', conflicto.reclutadoraNombre + ' ya tiene cita de ' + conflicto.horaInicio + ' a ' + conflicto.horaFin);
      renderCalendarioReclutadoras();
      return;
    }

    // Actualizar y guardar
    ent.fecha = newFecha;
    ent.hora = newHora;
    saveData();
    renderCalendarioReclutadoras();
    showToast('Entrevista reprogramada', newFecha + ' a las ' + newHora);
  });
}

function renderVacantesAsignadasPorReclutadora() {
  var container = document.getElementById('cal-rec-vacantes-asignadas');
  if (!container) return;

  var html = '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px;">';

  RECLUTADORAS.forEach(function(rec) {
    var vacRec = vacantes.filter(function(v) { return v.reclutadoraId === rec.id && v.estado === 'abierta'; });
    html += '<div style="border:1px solid var(--border);border-radius:var(--radius);padding:16px;border-left:4px solid ' + rec.color + ';">';
    html += '<h4 style="margin-bottom:12px;display:flex;align-items:center;gap:8px;">';
    html += '<span class="cal-rec-dot" style="background:' + rec.color + ';"></span> ' + escapeHtml(rec.nombre);
    html += ' <span style="font-weight:400;color:var(--muted);font-size:13px;">(' + vacRec.length + ' vacantes)</span></h4>';

    if (vacRec.length === 0) {
      html += '<p style="color:var(--muted);font-size:13px;">Sin vacantes asignadas</p>';
    } else {
      vacRec.forEach(function(v) {
        var candsVac = candidatos.filter(function(c) { return c.vacanteId === v.id && c.etapa !== 'rechazado'; });
        var entsVac = entrevistas.filter(function(e) { return e.reclutadoraId === rec.id && candsVac.some(function(c) { return c.id === e.candidatoId; }); });
        html += '<div style="padding:8px 0;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">';
        html += '<div>';
        html += '<span style="font-weight:600;font-size:13px;">' + escapeHtml(v.titulo) + '</span>';
        if (v.codigo) html += ' <span style="color:var(--primary);font-size:11px;font-weight:600;">' + escapeHtml(v.codigo) + '</span>';
        html += '<br><span style="font-size:12px;color:var(--muted);">' + escapeHtml(v.departamento) + '</span>';
        html += '</div>';
        html += '<div style="text-align:right;font-size:12px;">';
        html += '<span style="font-weight:600;">' + candsVac.length + '</span> candidatos<br>';
        html += '<span style="font-weight:600;">' + entsVac.length + '</span> entrevistas';
        html += '</div></div>';
      });
    }
    html += '</div>';
  });

  // Vacantes sin asignar
  var sinAsignar = vacantes.filter(function(v) { return !v.reclutadoraId && v.estado === 'abierta'; });
  if (sinAsignar.length > 0) {
    html += '<div style="border:1px solid var(--border);border-radius:var(--radius);padding:16px;border-left:4px solid #94a3b8;">';
    html += '<h4 style="margin-bottom:12px;display:flex;align-items:center;gap:8px;">';
    html += '<span class="cal-rec-dot" style="background:#94a3b8;"></span> Sin Asignar';
    html += ' <span style="font-weight:400;color:var(--muted);font-size:13px;">(' + sinAsignar.length + ' vacantes)</span></h4>';
    sinAsignar.forEach(function(v) {
      html += '<div style="padding:8px 0;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">';
      html += '<div>';
      html += '<span style="font-weight:600;font-size:13px;">' + escapeHtml(v.titulo) + '</span>';
      if (v.codigo) html += ' <span style="color:var(--primary);font-size:11px;font-weight:600;">' + escapeHtml(v.codigo) + '</span>';
      html += '<br><span style="font-size:12px;color:var(--muted);">' + escapeHtml(v.departamento) + '</span>';
      html += '</div>';
      html += '<div style="display:flex;gap:4px;">';
      RECLUTADORAS.forEach(function(r) {
        html += '<button class="btn btn-ghost btn-small" style="font-size:11px;border-color:' + r.color + ';color:' + r.color + ';" onclick="asignarReclutadoraVacante(\'' + v.id + '\',\'' + r.id + '\')">' + escapeHtml(r.nombreCorto) + '</button>';
      });
      html += '</div></div>';
    });
    html += '</div>';
  }

  html += '</div>';
  container.innerHTML = html;
}

function asignarReclutadoraVacante(vacId, recId) {
  var vac = vacantes.find(function(v) { return v.id == vacId; });
  if (!vac) return;
  vac.reclutadoraId = recId;
  var rec = RECLUTADORAS.find(function(r) { return r.id === recId; });
  saveData();
  showToast('Vacante asignada', vac.titulo + ' asignada a ' + (rec ? rec.nombre : 'reclutadora'));
  renderCalendarioReclutadoras();
}

function iniciarAltaEmpleado(candidatoId) {
  const candidato = candidatos.find(c => c.id === candidatoId);
  if (!candidato) return;

  const vacante = vacantes.find(v => v.id === candidato.vacanteId);

  document.getElementById('alta-candidato-id').value = candidatoId;
  document.getElementById('alta-puesto').value = vacante ? vacante.titulo : '';

  ['doc-ine', 'doc-nss', 'doc-curp', 'doc-rfc', 'doc-comprobante', 'doc-estudios'].forEach(id => {
    document.getElementById(id).checked = false;
  });
  updateAltaProgress();

  closeModal('detalle-candidato');
  openModal('alta-empleado');
}

function verCandidatosVacante(vacanteId) {
  showView('gestion-candidatos');
  document.getElementById('cand-filter-vacante').value = vacanteId;
  filtrarCandidatos();
}

// ==================== DASHBOARD ====================
function renderDashboard() {
  poblarFiltroVacantes();
  var data = computeDashboardData();
  renderDashboardKPIs(data);
  renderDashboardCharts(data);
}

function filtrarDashboard() {
  // Auto-rellenar Departamento y Vacante cuando la clave coincide exactamente
  var codigoInput = (document.getElementById('dash-codigo')?.value || '').trim().toUpperCase();
  if (codigoInput) {
    var match = vacantes.find(function(v) { return v.codigo && v.codigo.toUpperCase() === codigoInput; });
    if (match) {
      var deptoSelect = document.getElementById('dash-departamento');
      var vacanteSelect = document.getElementById('dash-vacante');
      if (deptoSelect) deptoSelect.value = match.departamento;
      // Primero poblar dropdown de vacantes con el depto seleccionado
      poblarFiltroVacantes();
      if (vacanteSelect) vacanteSelect.value = String(match.id);
    } else {
      poblarFiltroVacantes();
    }
  } else {
    poblarFiltroVacantes();
  }
  var data = computeDashboardData();
  renderDashboardKPIs(data);
  renderDashboardCharts(data);
}

function limpiarFiltrosDashboard() {
  document.getElementById('dash-codigo').value = '';
  document.getElementById('dash-departamento').value = '';
  document.getElementById('dash-vacante').value = '';
  document.getElementById('dash-estado').value = '';
  document.getElementById('dash-etapa').value = '';
  document.getElementById('dash-fecha-inicio').value = '';
  document.getElementById('dash-fecha-fin').value = '';
  filtrarDashboard();
}

function poblarFiltroVacantes() {
  var select = document.getElementById('dash-vacante');
  if (!select) return;
  var deptoFilter = document.getElementById('dash-departamento')?.value || '';
  var estadoFilter = document.getElementById('dash-estado')?.value || '';
  var currentVal = select.value;

  var opts = vacantes.filter(function(v) {
    if (deptoFilter && v.departamento !== deptoFilter) return false;
    if (estadoFilter && v.estado !== estadoFilter) return false;
    return true;
  });

  select.innerHTML = '<option value="">Todas</option>';
  opts.forEach(function(v) {
    var opt = document.createElement('option');
    opt.value = v.id;
    opt.textContent = (v.codigo ? v.codigo + ' - ' : '') + v.titulo + ' (' + v.departamento + ')';
    if (String(v.id) === currentVal) opt.selected = true;
    select.appendChild(opt);
  });
}

function computeDashboardData() {
  var fechaInicioRaw = document.getElementById('dash-fecha-inicio')?.value || '';
  var fechaFinRaw = document.getElementById('dash-fecha-fin')?.value || '';
  var fechaInicio = fechaDDMMAAAAaISO(fechaInicioRaw) || fechaInicioRaw;
  var fechaFin = fechaDDMMAAAAaISO(fechaFinRaw) || fechaFinRaw;
  var deptoFilter = document.getElementById('dash-departamento')?.value || '';
  var vacanteFilter = document.getElementById('dash-vacante')?.value || '';
  var estadoFilter = document.getElementById('dash-estado')?.value || '';
  var etapaFilter = document.getElementById('dash-etapa')?.value || '';
  var codigoFilter = (document.getElementById('dash-codigo')?.value || '').toUpperCase().trim();

  // 1. Filter vacantes
  var vacantesFiltradas = vacantes;

  if (codigoFilter) {
    vacantesFiltradas = vacantesFiltradas.filter(function(v) { return v.codigo && v.codigo.toUpperCase().includes(codigoFilter); });
  }
  if (deptoFilter) {
    vacantesFiltradas = vacantesFiltradas.filter(function(v) { return v.departamento === deptoFilter; });
  }
  if (estadoFilter) {
    vacantesFiltradas = vacantesFiltradas.filter(function(v) { return v.estado === estadoFilter; });
  }
  if (vacanteFilter) {
    vacantesFiltradas = vacantesFiltradas.filter(function(v) { return v.id === parseInt(vacanteFilter); });
  }
  if (fechaInicio || fechaFin) {
    var inicioV = fechaInicio ? new Date(fechaInicio) : new Date('2000-01-01');
    var finV = fechaFin ? new Date(fechaFin) : new Date('2099-12-31');
    vacantesFiltradas = vacantesFiltradas.filter(function(v) {
      var fecha = new Date(v.fechaCreacion);
      return fecha >= inicioV && fecha <= finV;
    });
  }

  // 2. Filter candidatos (only those belonging to filtered vacantes)
  var vacanteIds = {};
  vacantesFiltradas.forEach(function(v) { vacanteIds[v.id] = true; });

  var candidatosFiltrados = candidatos.filter(function(c) {
    return vacanteIds[c.vacanteId];
  });

  if (fechaInicio || fechaFin) {
    var inicioC = fechaInicio ? new Date(fechaInicio) : new Date('2000-01-01');
    var finC = fechaFin ? new Date(fechaFin) : new Date('2099-12-31');
    candidatosFiltrados = candidatosFiltrados.filter(function(c) {
      var fecha = new Date(c.fechaAplicacion);
      return fecha >= inicioC && fecha <= finC;
    });
  }

  if (etapaFilter) {
    candidatosFiltrados = candidatosFiltrados.filter(function(c) { return c.etapa === etapaFilter; });
  }

  // 3. Compute KPIs
  var totalVacantesActivas = vacantesFiltradas.filter(function(v) { return v.estado === 'abierta'; }).length;
  var vacantesCerradas = vacantesFiltradas.filter(function(v) { return v.estado === 'cerrada'; }).length;
  var totalCandidatos = candidatosFiltrados.length;
  var enProceso = candidatosFiltrados.filter(function(c) { return !['contratado', 'rechazado'].includes(c.etapa); }).length;
  var contratados = candidatosFiltrados.filter(function(c) { return c.etapa === 'contratado'; }).length;
  var rechazados = candidatosFiltrados.filter(function(c) { return c.etapa === 'rechazado'; }).length;

  var tasaContratacion = totalCandidatos > 0 ? Math.round((contratados / totalCandidatos) * 100) : 0;

  var diasPromedio = 0;
  var contratadosList = candidatosFiltrados.filter(function(c) { return c.etapa === 'contratado'; });
  if (contratadosList.length > 0) {
    var totalDias = contratadosList.reduce(function(sum, c) {
      return sum + Math.floor((new Date() - new Date(c.fechaAplicacion)) / (1000 * 60 * 60 * 24));
    }, 0);
    diasPromedio = Math.round(totalDias / contratadosList.length);
  }

  // Entrevistas agendadas (filtered by vacante scope + dates)
  var candidatoIds = {};
  candidatosFiltrados.forEach(function(c) { candidatoIds[c.id] = true; });

  var entrevistasAgendadas = entrevistas.filter(function(e) {
    if (!candidatoIds[e.candidatoId]) return false;
    if (fechaInicio || fechaFin) {
      var fecha = new Date(e.fecha);
      var ini = fechaInicio ? new Date(fechaInicio) : new Date('2000-01-01');
      var fin = fechaFin ? new Date(fechaFin) : new Date('2099-12-31');
      return fecha >= ini && fecha <= fin;
    }
    return true;
  }).length;

  var now = new Date();
  var startOfWeek = new Date(now);
  startOfWeek.setDate(now.getDate() - now.getDay());
  startOfWeek.setHours(0, 0, 0, 0);
  // Candidatos esta semana (within the filtered scope)
  var candidatosSemana = candidatosFiltrados.filter(function(c) { return new Date(c.fechaAplicacion) >= startOfWeek; }).length;

  // Pipeline distribution for donut
  var pipelineStages = [
    'aplicado', 'entrevista-rh', 'primer-filtro', 'entrevista-jefe',
    'revision-medica', 'psicometrico', 'referencias', 'documentos',
    'contratado', 'rechazado'
  ];
  var pipelineDistribution = {};
  pipelineStages.forEach(function(stage) {
    var count = candidatosFiltrados.filter(function(c) { return c.etapa === stage; }).length;
    if (count > 0) pipelineDistribution[stage] = count;
  });

  // Candidates per vacancy for bar chart
  var candidatosPorVacante = vacantesFiltradas.map(function(v) {
    return {
      titulo: v.titulo,
      count: candidatosFiltrados.filter(function(c) { return c.vacanteId === v.id; }).length
    };
  }).filter(function(item) { return item.count > 0; }).sort(function(a, b) { return b.count - a.count; }).slice(0, 10);

  var hiringTrend = computeHiringTrend(candidatosFiltrados);

  // Rejection distribution by stage (for donut chart)
  var rechazadosList = candidatosFiltrados.filter(function(c) { return c.etapa === 'rechazado'; });
  var rejectionByStage = {};
  rechazadosList.forEach(function(c) {
    var stageNum = c.etapaRechazo || 1;
    var stageName = getEtapaNombre(stageNum);
    if (!rejectionByStage[stageName]) rejectionByStage[stageName] = 0;
    rejectionByStage[stageName]++;
  });

  return {
    totalVacantesActivas: totalVacantesActivas, vacantesCerradas: vacantesCerradas,
    totalCandidatos: totalCandidatos, enProceso: enProceso,
    contratados: contratados, rechazados: rechazados,
    tasaContratacion: tasaContratacion, diasPromedio: diasPromedio,
    entrevistasAgendadas: entrevistasAgendadas, candidatosSemana: candidatosSemana,
    pipelineDistribution: pipelineDistribution, rejectionByStage: rejectionByStage,
    candidatosPorVacante: candidatosPorVacante, hiringTrend: hiringTrend
  };
}

function renderDashboardKPIs(data) {
  document.getElementById('rh-total-vacantes').textContent = data.totalVacantesActivas;
  document.getElementById('rh-total-candidatos').textContent = data.totalCandidatos;
  document.getElementById('rh-en-proceso').textContent = data.enProceso;
  document.getElementById('rh-contratados').textContent = data.contratados;
  document.getElementById('rh-rechazados').textContent = data.rechazados;
  document.getElementById('rh-dias-promedio').textContent = data.diasPromedio;
  document.getElementById('rh-tasa-contratacion').textContent = data.tasaContratacion + '%';
  document.getElementById('rh-vacantes-cerradas').textContent = data.vacantesCerradas;
  document.getElementById('rh-entrevistas-agendadas').textContent = data.entrevistasAgendadas;
  document.getElementById('rh-candidatos-semana').textContent = data.candidatosSemana;
}

// Filtros guardados del dashboard para poder volver
var _dashFiltrosGuardados = null;

function navegarDesdeKPI(tipo) {
  // Leer filtros actuales del dashboard
  var dashVacante = document.getElementById('dash-vacante')?.value || '';
  var dashDepto = document.getElementById('dash-departamento')?.value || '';
  var dashEstado = document.getElementById('dash-estado')?.value || '';
  var dashCodigo = document.getElementById('dash-codigo')?.value || '';

  // Guardar filtros para poder volver
  _dashFiltrosGuardados = { vacante: dashVacante, departamento: dashDepto, estado: dashEstado, codigo: dashCodigo };

  // KPIs que navegan a Vacantes RH
  if (tipo === 'total-vacantes' || tipo === 'vacantes-cerradas') {
    showView('gestion-vacantes');
    mostrarFlechaVolver('back-to-dash-vacantes');
    var estadoSelect = document.getElementById('vac-filter-estado');
    if (estadoSelect) {
      estadoSelect.value = tipo === 'total-vacantes' ? 'abierta' : 'cerrada';
    }
    if (dashDepto) {
      var deptoSelect = document.getElementById('vac-filter-departamento');
      if (deptoSelect) deptoSelect.value = dashDepto;
    }
    filtrarVacantes();
    return;
  }

  // KPIs que navegan a Candidatos
  showView('gestion-candidatos');
  mostrarFlechaVolver('back-to-dash-candidatos');

  // Setear filtro de departamento si hay uno seleccionado en el dashboard
  var candDeptoSelect = document.getElementById('cand-filter-departamento');
  if (candDeptoSelect) {
    candDeptoSelect.value = dashDepto || '';
  }

  // Repoblar vacantes filtradas por departamento
  var candVacanteSelect = document.getElementById('cand-filter-vacante');
  if (candVacanteSelect) {
    var vacsFiltradas = dashDepto ? vacantes.filter(function(v) { return v.departamento === dashDepto; }) : vacantes;
    candVacanteSelect.innerHTML = '<option value="">Todas las vacantes</option>' +
      vacsFiltradas.map(function(v) { return '<option value="' + v.id + '">' + escapeHtml(v.titulo) + '</option>'; }).join('');
    // Setear vacante específica si hay una seleccionada en el dashboard
    if (dashVacante) {
      candVacanteSelect.value = dashVacante;
    }
  }

  // Setear filtro de etapa según el KPI clickeado
  var candEtapaSelect = document.getElementById('cand-filter-etapa');
  if (candEtapaSelect) {
    var etapaMap = {
      'total-candidatos': '',
      'en-proceso': 'en-proceso',
      'contratados': 'contratado',
      'rechazados': 'rechazado',
      'entrevistas': '',
      'candidatos-semana': ''
    };
    candEtapaSelect.value = etapaMap[tipo] || '';
  }

  filtrarCandidatos();
}

function mostrarFlechaVolver(id) {
  // Ocultar todas las flechas primero
  document.querySelectorAll('.back-to-dash').forEach(function(el) { el.style.display = 'none'; });
  var arrow = document.getElementById(id);
  if (arrow) arrow.style.display = 'inline-flex';
}

function volverAlDashboard() {
  showView('gestion-dashboard');

  // Ocultar flechas de regreso
  document.querySelectorAll('.back-to-dash').forEach(function(el) { el.style.display = 'none'; });

  // Restaurar filtros del dashboard
  if (_dashFiltrosGuardados) {
    var f = _dashFiltrosGuardados;
    var elVac = document.getElementById('dash-vacante');
    var elDepto = document.getElementById('dash-departamento');
    var elEstado = document.getElementById('dash-estado');
    var elCodigo = document.getElementById('dash-codigo');
    if (elVac) elVac.value = f.vacante || '';
    if (elDepto) elDepto.value = f.departamento || '';
    if (elEstado) elEstado.value = f.estado || '';
    if (elCodigo) elCodigo.value = f.codigo || '';
    _dashFiltrosGuardados = null;
    filtrarDashboard();
  }
}

function computeHiringTrend(candidatosFiltrados) {
  const months = [];
  const now = new Date();
  for (let i = 5; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
    months.push({
      label: d.toLocaleString('es-MX', { month: 'short', year: '2-digit' }),
      year: d.getFullYear(),
      month: d.getMonth()
    });
  }

  const aplicados = months.map(m => {
    return candidatosFiltrados.filter(c => {
      const d = new Date(c.fechaAplicacion);
      return d.getFullYear() === m.year && d.getMonth() === m.month;
    }).length;
  });

  const contratados = months.map(m => {
    return candidatosFiltrados.filter(c => {
      if (c.etapa !== 'contratado') return false;
      const d = new Date(c.fechaAplicacion);
      return d.getFullYear() === m.year && d.getMonth() === m.month;
    }).length;
  });

  return { categories: months.map(m => m.label), aplicados, contratados };
}

// ---- ApexCharts config functions (same pattern as reporte_dashboard) ----

function renderRhFunnel(pipelineDistribution) {
  // Etapas secuenciales del pipeline (excluye 'rechazado' que no es secuencial)
  const stageOrder = [
    'contratado', 'documentos', 'referencias', 'psicometrico',
    'revision-medica', 'entrevista-jefe', 'primer-filtro', 'entrevista-rh', 'aplicado'
  ];

  const labels = [];
  const series = [];
  const colors = [];

  const activeColors = [
    '#22c55e', '#10b981', '#06b6d4', '#8b5cf6',
    '#f97316', '#ec4899', '#6366f1', '#f59e0b', '#3b82f6'
  ];
  const emptyColor = '#d1d5db';

  stageOrder.forEach(function(stage, i) {
    labels.push(getEtapaLabel(stage));
    var count = pipelineDistribution[stage] || 0;
    series.push(count > 0 ? count : 0.3);
    colors.push(count > 0 ? activeColors[i] : emptyColor);
  });

  return {
    chart: { type: 'bar', height: RH_CHART_HEIGHTS.funnel, toolbar: { show: true } },
    series: [{ name: 'Candidatos', data: series }],
    xaxis: { categories: labels },
    colors: colors,
    plotOptions: {
      bar: {
        borderRadius: 0,
        horizontal: true,
        barHeight: '80%',
        isFunnel: true,
        distributed: true
      }
    },
    dataLabels: {
      enabled: true,
      formatter: function(val, opts) {
        var realVal = val < 1 ? 0 : val;
        return labels[opts.dataPointIndex] + ': ' + realVal;
      },
      dropShadow: { enabled: false },
      style: { fontSize: '12px', colors: ['#fff'] }
    },
    legend: { show: false },
    tooltip: {
      enabled: true,
      y: { formatter: function(val) { return (val < 1 ? 0 : val) + ' candidatos'; } }
    }
  };
}

function renderRhRejectionDonut(rejectionByStage) {
  const stageOrder = [
    'aplicado', 'entrevista-rh', 'primer-filtro', 'entrevista-jefe',
    'revision-medica', 'psicometrico', 'referencias', 'documentos'
  ];

  const labels = [];
  const series = [];

  stageOrder.forEach(function(stage) {
    if (rejectionByStage[stage]) {
      labels.push(getEtapaLabel(stage));
      series.push(rejectionByStage[stage]);
    }
  });

  const total = series.reduce(function(a, b) { return a + b; }, 0);

  const colors = [
    '#ef4444', '#f97316', '#f59e0b', '#eab308',
    '#84cc16', '#22c55e', '#06b6d4', '#6366f1'
  ];

  return {
    chart: { type: 'donut', height: 280, toolbar: { show: true } },
    series: series,
    labels: labels,
    colors: colors.slice(0, labels.length),
    plotOptions: {
      pie: {
        donut: {
          size: '55%',
          labels: {
            show: true,
            name: { fontSize: '13px', offsetY: -10 },
            value: { show: true, fontSize: '14px', offsetY: -4 },
            total: {
              show: true,
              label: 'Total Rechazos',
              fontSize: '11px',
              formatter: function(w) {
                return w.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0);
              }
            }
          }
        }
      }
    },
    dataLabels: {
      formatter: function(val) { return val.toFixed(1) + '%'; }
    },
    tooltip: {
      y: {
        formatter: function(val) {
          var pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
          return val + ' rechazos (' + pct + '%)';
        }
      }
    },
    legend: {
      position: 'bottom',
      fontSize: '12px'
    }
  };
}

function renderRhLine(hiringTrend) {
  return {
    series: [
      { name: 'Postulaciones', data: hiringTrend.aplicados },
      { name: 'Contrataciones', data: hiringTrend.contratados }
    ],
    chart: { height: RH_CHART_HEIGHTS.line, type: 'line', zoom: { enabled: false } },
    xaxis: { categories: hiringTrend.categories },
    yaxis: {
      min: 0,
      labels: { formatter: function(val) { return Math.round(val); } }
    },
    stroke: { width: 2, curve: 'smooth' },
    markers: { size: 4, colors: ['hsl(230,86%,63%)', 'hsl(151,81%,28%)'] },
    tooltip: { shared: true, intersect: false },
    colors: ['hsl(230,86%,63%)', 'hsl(151,81%,28%)'],
    legend: { show: true, position: 'top', horizontalAlign: 'center', fontSize: '14px' }
  };
}

function renderRhGauge(tasaContratacion) {
  return {
    series: [tasaContratacion],
    chart: {
      height: RH_CHART_HEIGHTS.gauge,
      type: 'radialBar',
      offsetY: -20,
      sparkline: { enabled: true },
      animations: {
        enabled: true,
        easing: 'easeout',
        speed: 1200,
        dynamicAnimation: { enabled: true, speed: 1000 }
      }
    },
    plotOptions: {
      radialBar: {
        startAngle: -90,
        endAngle: 90,
        track: {
          background: '#e7e7e7',
          strokeWidth: '97%',
          margin: 10,
          dropShadow: { enabled: true, top: 2, left: 0, color: '#555', opacity: 1 }
        },
        dataLabels: {
          name: { show: false },
          value: { offsetY: -2, fontSize: '22px' }
        }
      }
    },
    grid: { padding: { top: -10 } },
    fill: { colors: ['hsl(151,81.4%,34.5%)'] }
  };
}

function renderRhBar(candidatosPorVacante) {
  const labels = candidatosPorVacante.map(function(item) {
    return item.titulo.length > 25 ? item.titulo.substring(0, 25) + '...' : item.titulo;
  });
  const values = candidatosPorVacante.map(function(item) { return item.count; });
  const maxVal = Math.max.apply(null, values.concat([5]));

  return {
    series: [{ data: values }],
    chart: { type: 'bar', height: RH_CHART_HEIGHTS.bar, toolbar: { show: true } },
    plotOptions: {
      bar: {
        borderRadius: 4,
        borderRadiusApplication: 'end',
        horizontal: true,
        distributed: true
      }
    },
    dataLabels: { enabled: true, style: { fontSize: '14px' } },
    xaxis: {
      categories: labels,
      min: 0,
      max: maxVal + 1,
      tickAmount: Math.min(5, maxVal)
    },
    tooltip: { enabled: false },
    colors: ['#003F2A', '#054C3A', '#0A664D', '#138C66', '#1DA97D', '#26B588', '#2DC496', '#36D3A4', '#40E2B2', '#4BF1C1'],
    grid: { borderColor: '#eee' },
    legend: { show: false }
  };
}

// ---- Chart lifecycle management ----

function renderDashboardCharts(data) {
  var funnelEl = document.getElementById('rhFunnelChart');
  var lineEl = document.getElementById('rhLineChart');
  var gaugeEl = document.getElementById('rhGaugeChart');
  var barEl = document.getElementById('rhBarChart');
  var rejectionEl = document.getElementById('rhRejectionChart');
  if (!funnelEl || !lineEl || !gaugeEl || !barEl) return;

  // Destroy existing charts
  if (rhFunnelChart) { rhFunnelChart.destroy(); rhFunnelChart = null; }
  if (rhLineChart) { rhLineChart.destroy(); rhLineChart = null; }
  if (rhGaugeChart) { rhGaugeChart.destroy(); rhGaugeChart = null; }
  if (rhBarChart) { rhBarChart.destroy(); rhBarChart = null; }
  if (rhRejectionChart) { rhRejectionChart.destroy(); rhRejectionChart = null; }

  // Clear containers
  funnelEl.innerHTML = '';
  lineEl.innerHTML = '';
  gaugeEl.innerHTML = '';
  barEl.innerHTML = '';
  if (rejectionEl) rejectionEl.innerHTML = '';

  // Funnel
  if (Object.keys(data.pipelineDistribution).length > 0) {
    rhFunnelChart = new ApexCharts(funnelEl, renderRhFunnel(data.pipelineDistribution));
    rhFunnelChart.render();
  } else {
    funnelEl.innerHTML = '<p style="text-align:center;color:#9ca3af;padding:40px 0;">Sin datos de candidatos</p>';
  }

  // Line
  rhLineChart = new ApexCharts(lineEl, renderRhLine(data.hiringTrend));
  rhLineChart.render();

  // Gauge
  rhGaugeChart = new ApexCharts(gaugeEl, renderRhGauge(data.tasaContratacion));
  rhGaugeChart.render();

  // Bar
  if (data.candidatosPorVacante.length > 0) {
    rhBarChart = new ApexCharts(barEl, renderRhBar(data.candidatosPorVacante));
    rhBarChart.render();
  } else {
    barEl.innerHTML = '<p style="text-align:center;color:#9ca3af;padding:40px 0;">Sin datos de vacantes</p>';
  }

  // Rejection Donut
  if (rejectionEl) {
    if (Object.keys(data.rejectionByStage).length > 0) {
      rhRejectionChart = new ApexCharts(rejectionEl, renderRhRejectionDonut(data.rejectionByStage));
      rhRejectionChart.render();
    } else {
      rejectionEl.innerHTML = '<p style="text-align:center;color:#9ca3af;padding:40px 0;">Sin rechazos registrados</p>';
    }
  }

}

// ---- Fullscreen toggle for RH dashboard ----
(function() {
  document.addEventListener('DOMContentLoaded', function() {
    var fsBtn = document.getElementById('rh-fullscreen-btn');
    if (!fsBtn) return;
    fsBtn.addEventListener('click', function() {
      var icon = fsBtn.querySelector('i');
      var isExpanded = icon.classList.contains('fa-expand');
      var menu = document.getElementById('menu');
      if (isExpanded) {
        icon.className = 'fas fa-compress';
        if (menu) menu.classList.add('hidden');
      } else {
        icon.className = 'fas fa-expand';
        if (menu) menu.classList.remove('hidden');
      }
    });
  });
})()

// ==================== SOLICITUDES DE VACANTES ====================

// --- Funciones auxiliares ---
function calcularEstadoSolicitud(sol) {
  if (sol.estado === 'publicada') return 'publicada';

  const d = sol.aprobacionDO?.estado || 'pendiente';
  const f = sol.aprobacionFinanzas?.estado || 'pendiente';

  if (d === 'rechazada' || f === 'rechazada') return 'rechazada';
  if (d === 'aprobada' && f === 'aprobada') return 'preaprobada';
  if (d === 'aprobada' && f === 'pendiente') return 'aprobada-do';
  return 'pendiente';
}

function getEstadoSolicitudLabel(estado) {
  const labels = {
    'pendiente': 'PENDIENTE',
    'aprobada-do': 'APROBADO POR D.O.',
    'preaprobada': 'PREAPROBADA',
    'rechazada': 'RECHAZADA',
    'publicada': 'PUBLICADA'
  };
  return labels[estado] || 'PENDIENTE';
}

function getBadgeClassSolicitud(estado) {
  const classes = {
    'pendiente': 'solicitud-pendiente',
    'aprobada-do': 'solicitud-aprobada-do',
    'preaprobada': 'solicitud-preaprobada',
    'rechazada': 'solicitud-rechazada',
    'publicada': 'solicitud-publicada'
  };
  return classes[estado] || 'solicitud-pendiente';
}

// --- Render de solicitudes (Jefe) ---
function renderSolicitudesJefe() {
  const grid = document.getElementById('solicitudesJefeGrid');
  if (!grid) return;

  const misSolicitudes = solicitudes.filter(s => s.solicitante === rolActual);

  if (misSolicitudes.length === 0) {
    grid.innerHTML = `
      <div class="empty-state">
        <h3>No hay solicitudes</h3>
        <p>Crea una nueva solicitud de vacante para iniciar el proceso de aprobaci\u00f3n</p>
      </div>
    `;
    return;
  }

  var esLista = vistaModos['solicitudesJefeGrid'] === 'lista';
  grid.classList.toggle('vista-lista', esLista);

  grid.innerHTML = misSolicitudes.map(sol => {
    const estadoCalc = calcularEstadoSolicitud(sol);
    if (esLista) {
      return `
      <div class="vacante-card" onclick="verDetalleSolicitud(${sol.id})" style="cursor:pointer">
        <div class="vacante-card-body">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
            <span class="vacante-status status-${getBadgeClassSolicitud(estadoCalc)}" style="margin-left:auto;">${getEstadoSolicitudLabel(estadoCalc)}</span>
          </div>
          <div class="vacante-info">
            <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
            <div class="vacante-info-item"><strong>Tipo:</strong> ${escapeHtml(sol.tipoContrato)}</div>
            <div class="vacante-info-item"><strong>Fecha:</strong> ${formatFecha(sol.fechaSolicitud)}</div>
            <div class="vacante-info-item"><strong>Vacantes:</strong> ${sol.cantidadVacantes || 1}</div>
          </div>
        </div>
        <div class="vacante-card-actions"><button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button></div>
      </div>`;
    }
    return `
      <div class="vacante-card" onclick="verDetalleSolicitud(${sol.id})" style="cursor:pointer">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
          <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
          <span class="vacante-status status-${getBadgeClassSolicitud(estadoCalc)}">${getEstadoSolicitudLabel(estadoCalc)}</span>
        </div>
        <div class="vacante-info">
          <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
          <div class="vacante-info-item"><strong>Tipo:</strong> ${escapeHtml(sol.tipoContrato)}</div>
          <div class="vacante-info-item"><strong>Fecha:</strong> ${formatFecha(sol.fechaSolicitud)}</div>
          ${sol.tipo ? `<div class="vacante-info-item"><strong>Solicitud:</strong> ${sol.tipo === 'existente' ? 'Puesto Existente' : 'Puesto Nuevo'}</div>` : ''}
          <div class="vacante-info-item"><strong>Vacantes:</strong> ${sol.cantidadVacantes || 1}</div>
        </div>
        <p class="vacante-desc">${escapeHtml(sol.justificacion).substring(0, 120)}...</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;">
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button>
        </div>
      </div>
    `;
  }).join('');
}

// --- Render de solicitudes (Gerentes) ---
function renderSolicitudesAprobacion() {
  const gridPendientes = document.getElementById('solicitudesAprobacionPendientes');
  const gridRevisadas = document.getElementById('solicitudesAprobacionRevisadas');
  if (!gridPendientes || !gridRevisadas) return;

  let pendientes, revisadas;

  if (rolActual === 'gerente-do') {
    pendientes = solicitudes.filter(s => calcularEstadoSolicitud(s) === 'pendiente');
    revisadas = solicitudes.filter(s => (s.aprobacionDO?.estado || 'pendiente') !== 'pendiente');
  } else if (rolActual === 'gerente-finanzas') {
    pendientes = solicitudes.filter(s => calcularEstadoSolicitud(s) === 'aprobada-do');
    revisadas = solicitudes.filter(s => (s.aprobacionFinanzas?.estado || 'pendiente') !== 'pendiente');
  } else {
    pendientes = [];
    revisadas = [];
  }

  var esListaPend = vistaModos['solicitudesAprobacionPendientes'] === 'lista';
  gridPendientes.classList.toggle('vista-lista', esListaPend);

  if (pendientes.length === 0) {
    gridPendientes.innerHTML = '<p style="padding:20px;text-align:center;color:var(--muted);">No hay solicitudes pendientes de revisi\u00f3n</p>';
  } else {
    gridPendientes.innerHTML = pendientes.map(sol => {
      if (esListaPend) {
        return `
        <div class="vacante-card" onclick="verDetalleSolicitud(${sol.id})" style="cursor:pointer">
          <div class="vacante-card-body">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
              <span class="vacante-status ${rolActual === 'gerente-finanzas' ? 'status-solicitud-aprobada-do' : 'status-solicitud-pendiente'}" style="margin-left:auto;">${rolActual === 'gerente-finanzas' ? 'APROBADO POR D.O.' : 'PENDIENTE'}</span>
            </div>
            <div class="vacante-info">
              <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
              <div class="vacante-info-item"><strong>Solicitante:</strong> Jefe de ${escapeHtml(sol.departamento)}</div>
              <div class="vacante-info-item"><strong>Fecha:</strong> ${formatFecha(sol.fechaSolicitud)}</div>
              <div class="vacante-info-item"><strong>Vacantes:</strong> ${sol.cantidadVacantes || 1}</div>
            </div>
          </div>
          <div class="vacante-card-actions"><button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button></div>
        </div>`;
      }
      return `
      <div class="vacante-card" onclick="verDetalleSolicitud(${sol.id})" style="cursor:pointer">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
          <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
          <span class="vacante-status ${rolActual === 'gerente-finanzas' ? 'status-solicitud-aprobada-do' : 'status-solicitud-pendiente'}">${rolActual === 'gerente-finanzas' ? 'APROBADO POR D.O.' : 'PENDIENTE'}</span>
        </div>
        <div class="vacante-info">
          <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
          <div class="vacante-info-item"><strong>Tipo:</strong> ${escapeHtml(sol.tipoContrato)}</div>
          <div class="vacante-info-item"><strong>Solicitante:</strong> Jefe de ${escapeHtml(sol.departamento)}</div>
          <div class="vacante-info-item"><strong>Fecha:</strong> ${formatFecha(sol.fechaSolicitud)}</div>
          <div class="vacante-info-item"><strong>Vacantes:</strong> ${sol.cantidadVacantes || 1}</div>
          ${rolActual === 'gerente-finanzas' && sol.aprobacionDO?.fecha ? `<div class="vacante-info-item"><strong>Aprobada por DO:</strong> ${formatFecha(sol.aprobacionDO.fecha)}</div>` : ''}
        </div>
        <p class="vacante-desc">${escapeHtml(sol.justificacion).substring(0, 120)}...</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;">
          <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button>
        </div>
      </div>`;
    }).join('');
  }

  var esListaRev = vistaModos['solicitudesAprobacionRevisadas'] === 'lista';
  gridRevisadas.classList.toggle('vista-lista', esListaRev);

  if (revisadas.length === 0) {
    gridRevisadas.innerHTML = '<p style="padding:20px;text-align:center;color:var(--muted);">No hay solicitudes revisadas a\u00fan</p>';
  } else {
    gridRevisadas.innerHTML = revisadas.map(sol => {
      const estadoCalc = calcularEstadoSolicitud(sol);
      const campoAprobacion = rolActual === 'gerente-finanzas' ? 'aprobacionFinanzas' : 'aprobacionDO';
      const miDecision = sol[campoAprobacion]?.estado || 'pendiente';
      if (esListaRev) {
        return `
        <div class="vacante-card" onclick="verDetalleSolicitud(${sol.id})" style="cursor:pointer">
          <div class="vacante-card-body">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
              <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
              <span class="vacante-status status-${getBadgeClassSolicitud(estadoCalc)}" style="margin-left:auto;">${getEstadoSolicitudLabel(estadoCalc)}</span>
            </div>
            <div class="vacante-info">
              <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
              <div class="vacante-info-item"><strong>Mi decisi\u00f3n:</strong> ${miDecision === 'aprobada' ? 'APROBADA' : 'RECHAZADA'}</div>
            </div>
          </div>
          <div class="vacante-card-actions"><button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button></div>
        </div>`;
      }
      return `
        <div class="vacante-card" onclick="verDetalleSolicitud(${sol.id})" style="cursor:pointer">
          <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
            <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
            <span class="vacante-status status-${getBadgeClassSolicitud(estadoCalc)}">${getEstadoSolicitudLabel(estadoCalc)}</span>
          </div>
          <div class="vacante-info">
            <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
            <div class="vacante-info-item"><strong>Mi decisi\u00f3n:</strong> ${miDecision === 'aprobada' ? 'APROBADA' : 'RECHAZADA'}</div>
            ${sol[campoAprobacion]?.comentario ? `<div class="vacante-info-item"><strong>Comentario:</strong> ${escapeHtml(sol[campoAprobacion].comentario)}</div>` : ''}
          </div>
          <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;">
            <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button>
          </div>
        </div>
      `;
    }).join('');
  }
}

// --- Render de preaprobadas (vista Vacantes RH) ---
function renderSolicitudesPreaprobadas() {
  const seccion = document.getElementById('seccion-preaprobadas');
  if (!seccion) return;

  const preaprobadas = solicitudes.filter(s => calcularEstadoSolicitud(s) === 'preaprobada');

  if (preaprobadas.length === 0) {
    seccion.style.display = 'none';
    return;
  }

  seccion.style.display = '';
  const grid = document.getElementById('preaprobadasGrid');
  var esLista = vistaModos['preaprobadasGrid'] === 'lista';
  grid.classList.toggle('vista-lista', esLista);

  grid.innerHTML = preaprobadas.map(sol => {
    if (esLista) {
      return `
      <div class="vacante-card" onclick="iniciarCompletarVacante(${sol.id})" style="cursor:pointer">
        <div class="vacante-card-body">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
            <span class="vacante-status status-solicitud-preaprobada" style="margin-left:auto;">APROBADA</span>
          </div>
          <div class="vacante-info">
            <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
            <div class="vacante-info-item"><strong>Solicitante:</strong> Jefe de ${escapeHtml(sol.departamento)}</div>
            <div class="vacante-info-item"><strong>Vacantes:</strong> ${sol.cantidadVacantes || 1}</div>
          </div>
        </div>
        <div class="vacante-card-actions"><button class="btn btn-ghost btn-small" onclick="event.stopPropagation();iniciarCompletarVacante(${sol.id})">Ver Aprobaciones y Publicar</button></div>
      </div>`;
    }
    return `
    <div class="vacante-card" onclick="iniciarCompletarVacante(${sol.id})" style="cursor:pointer">
      <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
        <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
        <span class="vacante-status status-solicitud-preaprobada">APROBADA</span>
      </div>
      <div class="vacante-info">
        <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</div>
        <div class="vacante-info-item"><strong>Tipo:</strong> ${escapeHtml(sol.tipoContrato)}</div>
        <div class="vacante-info-item"><strong>Solicitante:</strong> Jefe de ${escapeHtml(sol.departamento)}</div>
        <div class="vacante-info-item"><strong>Vacantes:</strong> ${sol.cantidadVacantes || 1}</div>
      </div>
      <div style="margin:12px 0;padding:10px 12px;background:#f0fdf4;border-radius:6px;font-size:12px;">
        <p style="margin:0 0 6px;font-weight:700;font-size:11px;text-transform:uppercase;color:var(--muted);letter-spacing:.5px;">Cadena de Aprobaciones</p>
        <p style="margin:0 0 4px;"><strong>DO:</strong> ${sol.aprobacionDO?.comentario ? escapeHtml(sol.aprobacionDO.comentario) : 'APROBADA'} <span style="color:var(--muted);">(${formatFecha(sol.aprobacionDO?.fecha)})</span></p>
        <p style="margin:0;"><strong>Finanzas:</strong> ${sol.aprobacionFinanzas?.comentario ? escapeHtml(sol.aprobacionFinanzas.comentario) : 'APROBADA'} <span style="color:var(--muted);">(${formatFecha(sol.aprobacionFinanzas?.fecha)})</span></p>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:auto;">
        <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();iniciarCompletarVacante(${sol.id})">Ver Aprobaciones y Publicar</button>
      </div>
    </div>`;
  }).join('');
}

// --- Detalle y aprobacion ---
function verDetalleSolicitud(id) {
  const sol = solicitudes.find(s => s.id === id);
  if (!sol) return;

  const estadoCalc = calcularEstadoSolicitud(sol);

  let puedeAprobar = false;
  if (rolActual === 'gerente-do' && estadoCalc === 'pendiente') {
    puedeAprobar = true;
  } else if (rolActual === 'gerente-finanzas' && estadoCalc === 'aprobada-do') {
    puedeAprobar = true;
  }

  // --- Sección de candidatos para jefes (solicitud publicada) ---
  let candidatosHtml = '';
  if (esRolJefe() && sol.vacanteId) {
    const candidatosVacante = candidatos.filter(c => c.vacanteId === sol.vacanteId);
    const porEtapa = {
      'aplicado': 0, 'entrevista-rh': 0, 'primer-filtro': 0,
      'entrevista-jefe': 0, 'revision-medica': 0, 'psicometrico': 0,
      'referencias': 0, 'documentos': 0, 'contratado': 0, 'rechazado': 0
    };
    candidatosVacante.forEach(c => {
      if (porEtapa.hasOwnProperty(c.etapa)) porEtapa[c.etapa]++;
    });
    const enProceso = candidatosVacante.filter(c => !['contratado', 'rechazado', 'aplicado'].includes(c.etapa)).length;
    const contratados = porEtapa['contratado'];
    const rechazados = porEtapa['rechazado'];
    const aplicados = porEtapa['aplicado'];

    candidatosHtml = `
    <div class="card">
      <div class="card-header"><h2>Estadísticas de Candidatos</h2></div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;">
        <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Total</div>
          <div style="font-size:24px;font-weight:900;color:var(--primary);">${candidatosVacante.length}</div>
        </div>
        <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Postulados</div>
          <div style="font-size:24px;font-weight:900;color:#3b82f6;">${aplicados}</div>
        </div>
        <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">En Proceso</div>
          <div style="font-size:24px;font-weight:900;color:#f59e0b;">${enProceso}</div>
        </div>
        <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Contratados</div>
          <div style="font-size:24px;font-weight:900;color:#10b981;">${contratados}</div>
        </div>
        <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Rechazados</div>
          <div style="font-size:24px;font-weight:900;color:#dc2626;">${rechazados}</div>
        </div>
      </div>
      ${(() => {
        const etapasActivas = Object.entries(porEtapa).filter(([etapa, count]) => count > 0);
        if (etapasActivas.length > 0) {
          return '<div style="margin-top:16px;"><p style="font-size:11px;text-transform:uppercase;color:var(--muted);letter-spacing:.7px;margin-bottom:8px;font-weight:800;">Candidatos por Etapa</p>' +
            etapasActivas.map(([etapa, count]) =>
              '<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 12px;border:1px solid var(--border);border-radius:6px;margin-bottom:4px;">' +
              '<span style="font-size:13px;">' + getEtapaLabel(etapa) + '</span>' +
              '<span class="badge badge-' + getBadgeClass(etapa) + '" style="white-space:nowrap;">' + count + '</span>' +
              '</div>'
            ).join('') + '</div>';
        }
        return '';
      })()}
    </div>

    <div class="card">
      <div class="card-header"><h2>Candidatos de esta Vacante</h2></div>
      ${candidatosVacante.length === 0
        ? '<p style="text-align:center;color:var(--muted);padding:20px 0;">No hay candidatos para esta vacante aún</p>'
        : '<div style="overflow-x:auto;"><table class="tabla-candidatos" style="width:100%;border-collapse:collapse;">' +
          '<thead><tr>' +
          '<th style="text-align:left;padding:10px 12px;border-bottom:2px solid var(--border);font-size:12px;text-transform:uppercase;color:var(--muted);">Nombre</th>' +
          '<th style="text-align:left;padding:10px 12px;border-bottom:2px solid var(--border);font-size:12px;text-transform:uppercase;color:var(--muted);">Etapa</th>' +
          '<th style="text-align:left;padding:10px 12px;border-bottom:2px solid var(--border);font-size:12px;text-transform:uppercase;color:var(--muted);">Fecha Aplicación</th>' +
          '<th style="text-align:center;padding:10px 12px;border-bottom:2px solid var(--border);font-size:12px;text-transform:uppercase;color:var(--muted);">Acciones</th>' +
          '</tr></thead><tbody>' +
          candidatosVacante.map(c =>
            '<tr>' +
            '<td style="padding:10px 12px;border-bottom:1px solid var(--border);">' + escapeHtml(c.nombre) + ' ' + escapeHtml(c.apellidos) + '</td>' +
            '<td style="padding:10px 12px;border-bottom:1px solid var(--border);"><span class="badge badge-' + getBadgeClass(c.etapa) + '" style="white-space:nowrap;">' + getEtapaLabel(c.etapa) + '</span></td>' +
            '<td style="padding:10px 12px;border-bottom:1px solid var(--border);">' + formatFecha(c.fechaAplicacion) + '</td>' +
            '<td style="padding:10px 12px;border-bottom:1px solid var(--border);text-align:center;"><button class="btn btn-ghost btn-small" onclick="closeModal(\'detalle-solicitud\');verDetalleCandidatoJefe(' + c.id + ')">Ver</button></td>' +
            '</tr>'
          ).join('') +
          '</tbody></table></div>'
      }
    </div>`;
  }

  const content = document.getElementById('detalle-solicitud-content');
  content.innerHTML = `
    <div class="card">
      <div class="card-header"><h2>Informaci\u00f3n de la Solicitud</h2></div>
      <div class="grid">
        <div class="col-6">
          <p><strong>T\u00edtulo:</strong> ${escapeHtml(sol.titulo)}</p>
          <p><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</p>
          ${sol.tipo ? `<p><strong>Tipo de solicitud:</strong> ${sol.tipo === 'existente' ? 'Puesto Existente' : 'Puesto Nuevo'}</p>` : ''}
          ${sol.jornada ? `<p><strong>Jornada:</strong> ${escapeHtml(sol.jornada)}</p>` : ''}
          ${sol.duracion ? `<p><strong>Duraci\u00f3n:</strong> ${escapeHtml(sol.duracion)}</p>` : ''}
        </div>
        <div class="col-6">
          <p><strong>Estado:</strong> <span class="vacante-status status-${getBadgeClassSolicitud(estadoCalc)}">${getEstadoSolicitudLabel(estadoCalc)}</span></p>
          <p><strong>Solicitante:</strong> Jefe de ${escapeHtml(sol.departamento)}</p>
          <p><strong>Fecha:</strong> ${formatFecha(sol.fechaSolicitud)}</p>
          <p><strong>Vacantes solicitadas:</strong> ${sol.cantidadVacantes || 1}</p>
          ${sol.sueldoDesde ? `<p><strong>Sueldo:</strong> $${Number(sol.sueldoDesde).toLocaleString('es-MX')} - $${Number(sol.sueldoHasta).toLocaleString('es-MX')}</p>` : ''}
          ${sol.ubicacion ? `<p><strong>Ubicaci\u00f3n:</strong> ${escapeHtml(UBICACION_NOMBRES[sol.ubicacion] || sol.ubicacion)}</p>` : ''}
          ${sol.direccion ? `<p><strong>Direcci\u00f3n:</strong> ${escapeHtml(sol.direccion)}</p>` : ''}
        </div>
        ${sol.descripcionPuesto ? `
        <div class="col-12" ${rolActual === 'gerente-do' ? 'style="background:#fffbeb;padding:12px;border-radius:8px;border:1px solid #fcd34d;"' : ''}>
          ${rolActual === 'gerente-do' ? '<p style="font-size:11px;color:#92400e;text-transform:uppercase;letter-spacing:.7px;margin:0 0 8px;">Revisar descripci\u00f3n del puesto</p>' : ''}
          <p><strong>Descripci\u00f3n del Puesto:</strong></p>
          <p>${escapeHtml(sol.descripcionPuesto)}</p>
        </div>
        ` : ''}
        ${sol.requisitosPuesto ? `
        <div class="col-12">
          <p><strong>Requisitos del Puesto:</strong></p>
          <p>${escapeHtml(sol.requisitosPuesto)}</p>
        </div>
        ` : ''}
        ${sol.actividades ? `
        <div class="col-12">
          <p><strong>Actividades:</strong></p>
          <p>${escapeHtml(sol.actividades)}</p>
        </div>
        ` : ''}
        ${sol.conocimientos ? `
        <div class="col-12">
          <p><strong>Conocimientos:</strong></p>
          <p>${escapeHtml(sol.conocimientos)}</p>
        </div>
        ` : ''}
        ${sol.ofrecemos ? `
        <div class="col-12">
          <p><strong>Ofrecemos:</strong></p>
          <p>${escapeHtml(sol.ofrecemos)}</p>
        </div>
        ` : ''}
        ${sol.beneficios ? `
        <div class="col-12">
          <p><strong>Beneficios:</strong></p>
          <p>${escapeHtml(sol.beneficios)}</p>
        </div>
        ` : ''}
        ${sol.horario ? `
        <div class="col-12">
          <p><strong>Horario:</strong></p>
          ${renderHorarioReadonly(sol.horario)}
        </div>
        ` : ''}
        <div class="col-12">
          <p><strong>Justificaci\u00f3n:</strong></p>
          <p>${escapeHtml(sol.justificacion)}</p>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h2>Estado de Aprobaciones (Secuencial)</h2></div>
      <div class="grid">
        <div class="col-6">
          <div style="padding:12px;border:1px solid var(--border);border-radius:8px;">
            <p style="font-size:11px;text-transform:uppercase;color:var(--muted);letter-spacing:.7px;margin:0 0 6px;">1. Gerente de Desarrollo Org.</p>
            <span class="vacante-status status-${getBadgeClassSolicitud(sol.aprobacionDO?.estado === 'aprobada' ? 'aprobada-do' : sol.aprobacionDO?.estado === 'rechazada' ? 'rechazada' : 'pendiente')}">
              ${sol.aprobacionDO?.estado === 'aprobada' ? 'APROBADA' : sol.aprobacionDO?.estado === 'rechazada' ? 'RECHAZADA' : 'PENDIENTE'}
            </span>
            ${sol.aprobacionDO?.comentario ? `<p style="margin-top:8px;font-size:13px;"><strong>Comentario:</strong> ${escapeHtml(sol.aprobacionDO.comentario)}</p>` : ''}
            ${sol.aprobacionDO?.fecha ? `<p style="font-size:12px;color:var(--muted);margin-top:4px;">${formatFecha(sol.aprobacionDO.fecha)}</p>` : ''}
          </div>
        </div>
        <div class="col-6">
          <div style="padding:12px;border:1px solid var(--border);border-radius:8px;">
            <p style="font-size:11px;text-transform:uppercase;color:var(--muted);letter-spacing:.7px;margin:0 0 6px;">2. Gerente de Finanzas</p>
            <span class="vacante-status status-${getBadgeClassSolicitud(sol.aprobacionFinanzas?.estado === 'aprobada' ? 'preaprobada' : sol.aprobacionFinanzas?.estado === 'rechazada' ? 'rechazada' : 'pendiente')}">
              ${sol.aprobacionFinanzas?.estado === 'aprobada' ? 'APROBADA' : sol.aprobacionFinanzas?.estado === 'rechazada' ? 'RECHAZADA' : 'PENDIENTE'}
            </span>
            ${(sol.aprobacionDO?.estado || 'pendiente') === 'pendiente' ? '<p style="margin-top:8px;font-size:12px;color:var(--muted);font-style:italic;">Esperando aprobaci\u00f3n de DO</p>' : ''}
            ${sol.aprobacionFinanzas?.comentario ? `<p style="margin-top:8px;font-size:13px;"><strong>Comentario:</strong> ${escapeHtml(sol.aprobacionFinanzas.comentario)}</p>` : ''}
            ${sol.aprobacionFinanzas?.fecha ? `<p style="font-size:12px;color:var(--muted);margin-top:4px;">${formatFecha(sol.aprobacionFinanzas.fecha)}</p>` : ''}
          </div>
        </div>
      </div>
    </div>

    ${puedeAprobar ? `
    <div class="card">
      <div class="card-header"><h2>Mi Decisi\u00f3n</h2></div>
      ${rolActual === 'gerente-do' ? '<p style="font-size:13px;color:var(--muted);margin-bottom:12px;">Revisa la descripci\u00f3n del puesto y los requisitos antes de aprobar.</p>' : ''}
      ${rolActual === 'gerente-finanzas' ? '<p style="font-size:13px;color:var(--muted);margin-bottom:12px;">Revisa el impacto presupuestal y el tipo de contrato antes de aprobar.</p>' : ''}
      <div style="margin-bottom:12px;">
        <label for="aprobacion-comentario">Comentario</label>
        <textarea id="aprobacion-comentario" placeholder="Escribe un comentario sobre tu decisi\u00f3n..."></textarea>
      </div>
      <div style="display:flex;gap:8px;">
        <button class="btn btn-primary" onclick="procesarAprobacion(${sol.id}, 'aprobada')">Aprobar Solicitud</button>
        <button class="btn btn-danger" onclick="procesarAprobacion(${sol.id}, 'rechazada')">Rechazar Solicitud</button>
      </div>
    </div>
    ` : ''}

    ${candidatosHtml}
  `;

  openModal('detalle-solicitud');
}

function procesarAprobacion(id, decision) {
  const sol = solicitudes.find(s => s.id === id);
  if (!sol) return;

  const comentario = document.getElementById('aprobacion-comentario')?.value || '';
  const fecha = new Date().toISOString().split('T')[0];

  if (rolActual === 'gerente-do') {
    sol.aprobacionDO = { estado: decision, comentario, fecha };
    if (decision === 'aprobada') {
      crearNotificacion('gerente-finanzas', `Solicitud aprobada por DO: ${sol.titulo}`, { vista: 'solicitudes-aprobacion', solicitudId: sol.id });
    } else {
      crearNotificacion(sol.solicitante, `Solicitud rechazada por DO: ${sol.titulo}`, { vista: 'solicitudes-jefe', solicitudId: sol.id });
    }
  } else if (rolActual === 'gerente-finanzas') {
    sol.aprobacionFinanzas = { estado: decision, comentario, fecha };
    if (decision === 'aprobada') {
      crearNotificacion('rh', `Vacante preaprobada: ${sol.titulo}`, { vista: 'gestion-vacantes', solicitudId: sol.id });
    } else {
      crearNotificacion(sol.solicitante, `Solicitud rechazada por Finanzas: ${sol.titulo}`, { vista: 'solicitudes-jefe', solicitudId: sol.id });
    }
  }

  sol.estado = calcularEstadoSolicitud(sol);
  saveData();

  closeModal('detalle-solicitud');
  showToast(
    decision === 'aprobada' ? 'Solicitud aprobada' : 'Solicitud rechazada',
    `La solicitud "${sol.titulo}" ha sido ${decision === 'aprobada' ? 'aprobada' : 'rechazada'}`
  );
  renderSolicitudesAprobacion();
}

function abrirAprobacion(id, accion) {
  verDetalleSolicitud(id);
}

// --- Completar vacante (RH) ---
function iniciarCompletarVacante(id) {
  const sol = solicitudes.find(s => s.id === id);
  if (!sol) return;

  document.getElementById('completar-solicitud-id').value = sol.id;
  document.getElementById('completar-info').innerHTML = `
    <div class="grid">
      <div class="col-6"><p><strong>T\u00edtulo:</strong> ${escapeHtml(sol.titulo)}</p></div>
      <div class="col-6"><p><strong>Departamento:</strong> ${escapeHtml(sol.departamento)}</p></div>
      <div class="col-6"><p><strong>Jornada:</strong> ${escapeHtml(sol.jornada || 'No disponible')}</p></div>
      <div class="col-6"><p><strong>Duraci\u00f3n:</strong> ${escapeHtml(sol.duracion || 'No disponible')}</p></div>
      <div class="col-6"><p><strong>Sueldo:</strong> ${sol.sueldoDesde ? '$' + Number(sol.sueldoDesde).toLocaleString('es-MX') + ' - $' + Number(sol.sueldoHasta).toLocaleString('es-MX') : 'No disponible'}</p></div>
      <div class="col-6"><p><strong>Ubicaci\u00f3n:</strong> ${escapeHtml(UBICACION_NOMBRES[sol.ubicacion] || sol.ubicacion || 'No disponible')}</p></div>
      ${sol.direccion ? `<div class="col-12"><p><strong>Direcci\u00f3n:</strong> ${escapeHtml(sol.direccion)}</p></div>` : ''}
      <div class="col-12"><p><strong>Vacantes solicitadas:</strong> ${sol.cantidadVacantes || 1}</p></div>
      ${sol.horario ? `<div class="col-12">${renderHorarioReadonly(sol.horario)}</div>` : ''}
    </div>

    <div style="margin-top:16px;">
      <p style="font-size:11px;text-transform:uppercase;color:var(--muted);letter-spacing:.7px;margin-bottom:10px;font-weight:800;">Cadena de Aprobaciones</p>
      <div style="display:grid;gap:8px;">
        <div style="padding:12px;border:1px solid #bbf7d0;border-radius:8px;background:#f0fdf4;">
          <p style="margin:0 0 4px;font-weight:700;color:#166534;">&#10003; Gerente de Desarrollo Org.</p>
          <p style="margin:0;font-size:13px;">Aprobada el ${formatFecha(sol.aprobacionDO?.fecha)}</p>
          ${sol.aprobacionDO?.comentario ? `<p style="margin:4px 0 0;font-size:13px;color:var(--muted);font-style:italic;">"${escapeHtml(sol.aprobacionDO.comentario)}"</p>` : ''}
        </div>
        <div style="padding:12px;border:1px solid #bbf7d0;border-radius:8px;background:#f0fdf4;">
          <p style="margin:0 0 4px;font-weight:700;color:#166534;">&#10003; Gerente de Finanzas</p>
          <p style="margin:0;font-size:13px;">Aprobada el ${formatFecha(sol.aprobacionFinanzas?.fecha)}</p>
          ${sol.aprobacionFinanzas?.comentario ? `<p style="margin:4px 0 0;font-size:13px;color:var(--muted);font-style:italic;">"${escapeHtml(sol.aprobacionFinanzas.comentario)}"</p>` : ''}
        </div>
      </div>
    </div>
  `;

  document.getElementById('completar-ubicacion').value = sol.direccion || '';
  document.getElementById('completar-salario').value = sol.sueldoDesde ? `$${Number(sol.sueldoDesde).toLocaleString('es-MX')} - $${Number(sol.sueldoHasta).toLocaleString('es-MX')}` : '';
  document.getElementById('completar-descripcion').value = sol.descripcionPuesto || '';
  document.getElementById('completar-actividades').value = sol.actividades || '';
  document.getElementById('completar-requisitos').value = sol.requisitosPuesto || '';
  document.getElementById('completar-conocimientos').value = sol.conocimientos || '';
  document.getElementById('completar-ofrecemos').value = sol.ofrecemos || '';
  document.getElementById('completar-beneficios').value = sol.beneficios || '';

  openModal('completar-vacante');
}

// --- Flujo nueva solicitud (jefe) ---
function abrirNuevaSolicitud() {
  mostrarPasoSolicitud('1');
  cargarPuestosExistentes();
  const depto = getDepartamentoJefe();
  if (depto) {
    const deptoInput = document.getElementById('sol-nuevo-departamento');
    if (deptoInput) deptoInput.value = depto;
  }
  // Reset forms
  const formEx = document.getElementById('form-solicitud-existente');
  if (formEx) formEx.reset();
  const formNuevo = document.getElementById('form-solicitud-nuevo');
  if (formNuevo) formNuevo.reset();
  if (depto) {
    const deptoInput = document.getElementById('sol-nuevo-departamento');
    if (deptoInput) deptoInput.value = depto;
  }
  const preview = document.getElementById('sol-preview');
  if (preview) preview.style.display = 'none';

  // Limpiar wrappers de horario
  ['ex', 'nuevo'].forEach(p => {
    const w = document.getElementById(`sol-${p}-horario-wrapper`);
    if (w) { w.style.display = 'none'; w.innerHTML = ''; }
  });

  openModal('nueva-solicitud');
}

function seleccionarTipoSolicitud(tipo) {
  if (tipo === 'existente') {
    mostrarPasoSolicitud('2a');
    cargarPuestosExistentes();
  } else {
    mostrarPasoSolicitud('2b');
    const depto = getDepartamentoJefe();
    if (depto) {
      const deptoInput = document.getElementById('sol-nuevo-departamento');
      if (deptoInput) deptoInput.value = depto;
    }
  }
}

function mostrarPasoSolicitud(paso) {
  document.querySelectorAll('.sol-paso').forEach(p => p.style.display = 'none');
  const target = document.getElementById(`sol-paso-${paso}`);
  if (target) target.style.display = 'block';
}

function cargarPuestosExistentes() {
  const depto = getDepartamentoJefe();
  if (!depto) return;

  const puestos = getPuestosUnicos(depto);
  const select = document.getElementById('sol-puesto-existente');
  if (!select) return;

  select.innerHTML = '<option value="">Seleccionar puesto...</option>' +
    puestos.map(p => `<option value="${escapeHtml(p.puesto)}">${escapeHtml(p.puesto)}</option>`).join('');
}

function previsualizarPuesto() {
  const select = document.getElementById('sol-puesto-existente');
  const preview = document.getElementById('sol-preview');
  if (!select || !preview) return;

  const puestoNombre = select.value;
  if (!puestoNombre) {
    preview.style.display = 'none';
    return;
  }

  const depto = getDepartamentoJefe();
  const puestos = getPuestosUnicos(depto);
  const puesto = puestos.find(p => p.puesto === puestoNombre);

  if (!puesto) return;

  preview.style.display = '';
  document.getElementById('sol-ex-titulo').value = puesto.puesto;
  document.getElementById('sol-ex-departamento').value = puesto.departamento;
  document.getElementById('sol-ex-tipo-contrato').value = puesto.tipoContrato;
  document.getElementById('sol-ex-descripcion').value = puesto.descripcionPuesto;
  document.getElementById('sol-ex-requisitos').value = puesto.requisitosPuesto;
  document.getElementById('sol-ex-justificacion').value = '';
}

// --- Horario y Ubicacion helpers ---
const DIRECCIONES = {
  'planta1': 'Elite Perif\u00e9rico Oriente I Industrial - A05, Cam. Al Vado, 45429 Tonal\u00e1, Jal.',
  'planta2': 'C. Artes 2767, San Rafael, 44810 Guadalajara, Jal.',
  'corporativo': 'Av. Ignacio L Vallarta 2025, Col Americana, Lafayette, 44130 Guadalajara, Jal.',
  'cedis': 'Elite Perif\u00e9rico Oriente I Industrial | Bodega D-04, Cam. Al Vado, 45429 Tonal\u00e1, Jal.'
};

const UBICACION_NOMBRES = {
  'planta1': 'Planta 1 Tonal\u00e1',
  'planta2': 'Planta 2 Artes',
  'corporativo': 'Corporativo Vallarta',
  'cedis': 'CEDIS Tonal\u00e1'
};

function llenarDireccion(prefix) {
  const sel = document.getElementById(`sol-${prefix}-ubicacion`);
  const dir = document.getElementById(`sol-${prefix}-direccion`);
  if (!sel || !dir) return;
  dir.value = DIRECCIONES[sel.value] || '';
}

function generarTablaHorario(prefix) {
  const sel = document.getElementById(`sol-${prefix}-turno`);
  const wrapper = document.getElementById(`sol-${prefix}-horario-wrapper`);
  if (!sel || !wrapper) return;

  if (!sel.value) {
    wrapper.style.display = 'none';
    wrapper.innerHTML = '';
    return;
  }

  const dias = sel.value === 'L-V'
    ? ['Lunes', 'Martes', 'Mi\u00e9rcoles', 'Jueves', 'Viernes']
    : ['Lunes', 'Martes', 'Mi\u00e9rcoles', 'Jueves', 'Viernes', 'S\u00e1bado'];

  wrapper.style.display = 'block';
  wrapper.innerHTML = `
    <div class="horario-tabla">
      <div class="horario-fila horario-header">
        <span>D\u00eda</span><span>Entrada</span><span>Salida</span>
      </div>
      ${dias.map(d => {
        const key = d.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
        return `
        <div class="horario-fila">
          <span class="horario-dia">${d}</span>
          <input type="time" id="horario-${prefix}-${key}-entrada">
          <input type="time" id="horario-${prefix}-${key}-salida">
        </div>`;
      }).join('')}
    </div>
    <div class="horario-aplicar">
      <p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);">Aplicar a todos los d\u00edas</p>
      <div class="horario-fila">
        <span></span>
        <input type="time" id="horario-${prefix}-todos-entrada" value="09:00">
        <input type="time" id="horario-${prefix}-todos-salida" value="18:00">
        <button type="button" class="btn btn-primary btn-small" onclick="aplicarHorarioTodos('${prefix}')">Aplicar</button>
      </div>
    </div>
  `;
}

function aplicarHorarioTodos(prefix) {
  const entrada = document.getElementById(`horario-${prefix}-todos-entrada`).value;
  const salida = document.getElementById(`horario-${prefix}-todos-salida`).value;
  const wrapper = document.getElementById(`sol-${prefix}-horario-wrapper`);
  if (!wrapper) return;

  wrapper.querySelectorAll('.horario-tabla .horario-fila:not(.horario-header)').forEach(fila => {
    const inputs = fila.querySelectorAll('input[type="time"]');
    if (inputs[0]) inputs[0].value = entrada;
    if (inputs[1]) inputs[1].value = salida;
  });
}

function recogerHorario(prefix) {
  const turnoSel = document.getElementById(`sol-${prefix}-turno`);
  if (!turnoSel || !turnoSel.value) return null;

  const dias = turnoSel.value === 'L-V'
    ? ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes']
    : ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];

  const resultado = { turno: turnoSel.value, dias: {} };
  dias.forEach(d => {
    const key = d.toLowerCase();
    const ent = document.getElementById(`horario-${prefix}-${key}-entrada`);
    const sal = document.getElementById(`horario-${prefix}-${key}-salida`);
    resultado.dias[d] = {
      entrada: ent ? ent.value : '',
      salida: sal ? sal.value : ''
    };
  });
  return resultado;
}

function renderHorarioReadonly(horario) {
  if (!horario || !horario.dias) return '<p style="color:var(--muted);">No especificado</p>';
  const turnoLabel = horario.turno === 'L-V' ? 'Lunes a Viernes' : 'Lunes a S\u00e1bado';
  let html = `<p><strong>Turno:</strong> ${turnoLabel}</p><div class="horario-tabla horario-readonly">`;
  html += '<div class="horario-fila horario-header"><span>D\u00eda</span><span>Entrada</span><span>Salida</span></div>';
  for (const [dia, h] of Object.entries(horario.dias)) {
    html += `<div class="horario-fila"><span>${escapeHtml(dia)}</span><span>${h.entrada || '-'}</span><span>${h.salida || '-'}</span></div>`;
  }
  html += '</div>';
  return html;
}

// --- Form: Solicitudes (existente y nuevo) ---
function setupFormsSolicitud() {
  const formExistente = document.getElementById('form-solicitud-existente');
  if (formExistente) {
    formExistente.addEventListener('submit', (e) => {
      e.preventDefault();

      const depto = getDepartamentoJefe();
      const solicitud = {
        id: Date.now(),
        titulo: document.getElementById('sol-ex-titulo').value,
        departamento: depto,
        justificacion: document.getElementById('sol-ex-justificacion').value,
        descripcionPuesto: document.getElementById('sol-ex-descripcion').value,
        requisitosPuesto: document.getElementById('sol-ex-requisitos').value,
        tipo: 'existente',
        cantidadVacantes: parseInt(document.getElementById('sol-cantidad').value) || 1,
        puestoReferencia: document.getElementById('sol-puesto-existente').value,
        solicitante: rolActual,
        fechaSolicitud: new Date().toISOString().split('T')[0],
        estado: 'pendiente',
        aprobacionFinanzas: { estado: 'pendiente', comentario: '', fecha: '' },
        aprobacionDO: { estado: 'pendiente', comentario: '', fecha: '' },
        sueldoDesde: document.getElementById('sol-ex-sueldo-desde').value,
        sueldoHasta: document.getElementById('sol-ex-sueldo-hasta').value,
        jornada: document.getElementById('sol-ex-jornada').value,
        duracion: document.getElementById('sol-ex-duracion').value,
        horario: recogerHorario('ex'),
        ubicacion: document.getElementById('sol-ex-ubicacion').value,
        direccion: document.getElementById('sol-ex-direccion').value,
        actividades: document.getElementById('sol-ex-actividades').value,
        conocimientos: document.getElementById('sol-ex-conocimientos').value,
        vacanteId: null
      };

      solicitudes.push(solicitud);
      saveData();

      crearNotificacion('gerente-do', `Nueva solicitud de vacante: ${solicitud.titulo}`, { vista: 'solicitudes-aprobacion', solicitudId: solicitud.id });

      showToast('Solicitud creada', 'Tu solicitud ha sido enviada para aprobaci\u00f3n');
      closeModal('nueva-solicitud');
      formExistente.reset();
      renderSolicitudesJefe();
    });
  }

  const formNuevo = document.getElementById('form-solicitud-nuevo');
  if (formNuevo) {
    formNuevo.addEventListener('submit', (e) => {
      e.preventDefault();

      const depto = getDepartamentoJefe();
      const solicitud = {
        id: Date.now(),
        titulo: document.getElementById('sol-nuevo-titulo').value,
        departamento: depto,
        justificacion: document.getElementById('sol-nuevo-justificacion').value,
        descripcionPuesto: document.getElementById('sol-nuevo-descripcion').value,
        requisitosPuesto: document.getElementById('sol-nuevo-requisitos').value,
        tipo: 'nuevo',
        cantidadVacantes: 1,
        puestoReferencia: null,
        solicitante: rolActual,
        fechaSolicitud: new Date().toISOString().split('T')[0],
        estado: 'pendiente',
        aprobacionFinanzas: { estado: 'pendiente', comentario: '', fecha: '' },
        aprobacionDO: { estado: 'pendiente', comentario: '', fecha: '' },
        sueldoDesde: document.getElementById('sol-nuevo-sueldo-desde').value,
        sueldoHasta: document.getElementById('sol-nuevo-sueldo-hasta').value,
        jornada: document.getElementById('sol-nuevo-jornada').value,
        duracion: document.getElementById('sol-nuevo-duracion').value,
        horario: recogerHorario('nuevo'),
        ubicacion: document.getElementById('sol-nuevo-ubicacion').value,
        direccion: document.getElementById('sol-nuevo-direccion').value,
        actividades: document.getElementById('sol-nuevo-actividades').value,
        conocimientos: document.getElementById('sol-nuevo-conocimientos').value,
        vacanteId: null
      };

      solicitudes.push(solicitud);
      saveData();

      crearNotificacion('gerente-do', `Nueva solicitud de vacante: ${solicitud.titulo}`, { vista: 'solicitudes-aprobacion', solicitudId: solicitud.id });

      showToast('Solicitud creada', 'Tu solicitud ha sido enviada para aprobaci\u00f3n');
      closeModal('nueva-solicitud');
      formNuevo.reset();
      renderSolicitudesJefe();
    });
  }
}

// --- Form: Completar Vacante ---
function setupFormCompletarVacante() {
  const form = document.getElementById('form-completar-vacante');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const solId = parseInt(document.getElementById('completar-solicitud-id').value);
    const sol = solicitudes.find(s => s.id === solId);
    if (!sol) return;

    const vacante = {
      id: Date.now(),
      titulo: sol.titulo,
      departamento: sol.departamento,
      ubicacion: document.getElementById('completar-ubicacion').value,
      tipo: sol.jornada || sol.tipoContrato || '',
      salario: document.getElementById('completar-salario').value,
      descripcion: document.getElementById('completar-descripcion').value,
      actividades: document.getElementById('completar-actividades').value,
      requisitos: document.getElementById('completar-requisitos').value,
      conocimientos: document.getElementById('completar-conocimientos').value,
      ofrecemos: document.getElementById('completar-ofrecemos').value,
      beneficios: document.getElementById('completar-beneficios').value,
      estado: 'abierta',
      fechaCreacion: new Date().toISOString().split('T')[0],
      jornada: sol.jornada || '',
      duracion: sol.duracion || '',
      horario: sol.horario || null,
      ubicacionClave: sol.ubicacion || '',
      direccion: sol.direccion || '',
      solicitarCV: !!document.getElementById('completar-solicitar-cv').checked,
      mostrarSalario: !!document.getElementById('completar-mostrar-salario').checked
    };

    vacantes.push(vacante);

    sol.estado = 'publicada';
    sol.ubicacion = vacante.ubicacion;
    sol.salario = vacante.salario;
    sol.descripcion = vacante.descripcion;
    sol.requisitos = vacante.requisitos;
    sol.vacanteId = vacante.id;

    saveData();

    crearNotificacion(sol.solicitante, `Vacante publicada: ${sol.titulo}`, { vista: 'solicitudes-jefe' });

    showToast('Vacante publicada', 'La vacante ha sido creada y publicada exitosamente');
    closeModal('completar-vacante');
    e.target.reset();
    renderVacantesGestion();
    renderSolicitudesPreaprobadas();
    if (document.getElementById('vacantesGrid')) renderVacantesPortal();
  });
}

// ==================== NOTIFICACIONES ====================
function crearNotificacion(destinatario, mensaje, link) {
  const notif = {
    id: 'n' + Date.now() + Math.floor(Math.random() * 1000),
    destinatario,
    mensaje,
    fecha: new Date().toISOString(),
    leida: false,
    link: link || null
  };
  notificaciones.push(notif);
  saveData();
  contarNoLeidas();
}

function toggleNotificaciones() {
  const sidebar = document.getElementById('sidebar-notificaciones');
  if (sidebar) {
    sidebar.classList.toggle('open');
    if (sidebar.classList.contains('open')) {
      renderNotificaciones();
    }
  }
}

function renderNotificaciones() {
  const lista = document.getElementById('notificaciones-lista');
  if (!lista) return;

  const misNotifs = notificaciones
    .filter(n => n.destinatario === rolActual)
    .sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

  if (misNotifs.length === 0) {
    lista.innerHTML = '<p style="padding:20px;text-align:center;color:var(--muted);">No hay notificaciones</p>';
    return;
  }

  lista.innerHTML = misNotifs.map(n => `
    <div class="notif-item ${n.leida ? '' : 'no-leida'}" onclick="clickNotificacion('${n.id}')">
      <p class="notif-mensaje">${escapeHtml(n.mensaje)}</p>
      <span class="notif-fecha">${new Date(n.fecha).toLocaleDateString('es-MX', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })}</span>
    </div>
  `).join('');
}

function contarNoLeidas() {
  const count = notificaciones.filter(n => n.destinatario === rolActual && !n.leida).length;
  const badge = document.getElementById('notif-badge');
  if (badge) {
    badge.textContent = count;
    badge.style.display = count > 0 ? '' : 'none';
  }
}

function clickNotificacion(id) {
  const notif = notificaciones.find(n => String(n.id) === String(id));
  if (!notif) return;

  notif.leida = true;
  saveData();
  contarNoLeidas();

  if (notif.link) {
    toggleNotificaciones();
    if (notif.link.vista) {
      showView(notif.link.vista);
    }
    if (notif.link.solicitudId) {
      const solNotif = solicitudes.find(s => s.id === notif.link.solicitudId);
      if (solNotif && calcularEstadoSolicitud(solNotif) === 'preaprobada') {
        setTimeout(() => iniciarCompletarVacante(notif.link.solicitudId), 300);
      } else {
        setTimeout(() => verDetalleSolicitud(notif.link.solicitudId), 300);
      }
    }
    if (notif.link.candidatoId) {
      setTimeout(() => verDetalleCandidato(notif.link.candidatoId), 300);
    }
  }

  renderNotificaciones();
}

// ==================== FORMS ====================
// Form: Nueva Vacante
function setupFormListeners() {
  const formNuevaVacante = document.getElementById('form-nueva-vacante');
  if (formNuevaVacante) {
    formNuevaVacante.addEventListener('submit', (e) => {
      e.preventDefault();

      var recSel = document.getElementById('vac-reclutadora');
      var reclutadoraId = recSel ? recSel.value : '';

      const vacante = {
        id: Date.now(),
        codigo: generarCodigoVacante(),
        titulo: document.getElementById('vac-titulo').value,
        departamento: document.getElementById('vac-departamento').value,
        ubicacion: document.getElementById('vac-ubicacion').value,
        tipo: document.getElementById('vac-tipo').value,
        salario: document.getElementById('vac-salario').value,
        descripcion: document.getElementById('vac-descripcion').value,
        requisitos: document.getElementById('vac-requisitos').value,
        reclutadoraId: reclutadoraId || null,
        estado: 'abierta',
        fechaCreacion: new Date().toISOString().split('T')[0]
      };

      vacantes.push(vacante);
      saveData();

      showToast('Vacante creada', 'La vacante ha sido publicada exitosamente');
      closeModal('nueva-vacante');
      e.target.reset();
      renderVacantesGestion();
      if (document.getElementById('vacantesGrid')) renderVacantesPortal();
    });
  }
}

// Form: Aplicar a Vacante
const CV_MAX_SIZE = 2 * 1024 * 1024;
const CV_TIPOS_PERMITIDOS = [
  'application/pdf',
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

function leerArchivoCV(fileInput) {
  return new Promise((resolve, reject) => {
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
      resolve(null);
      return;
    }

    const archivo = fileInput.files[0];

    if (!CV_TIPOS_PERMITIDOS.includes(archivo.type)) {
      reject(new Error('Formato de archivo no v\u00e1lido. Solo se permiten PDF, DOC y DOCX.'));
      return;
    }

    if (archivo.size > CV_MAX_SIZE) {
      reject(new Error('El archivo excede el tama\u00f1o m\u00e1ximo de 2MB.'));
      return;
    }

    const reader = new FileReader();
    reader.onload = () => {
      resolve({
        nombre: archivo.name,
        tipo: archivo.type,
        data: reader.result
      });
    };
    reader.onerror = () => reject(new Error('Error al leer el archivo.'));
    reader.readAsDataURL(archivo);
  });
}

// --- Habilidades Tags ---
let habilidadesLista = [];

function agregarHabilidad() {
  const input = document.getElementById('apli-habilidad-input');
  if (!input) return;
  const valor = input.value.trim();
  if (!valor) return;
  if (habilidadesLista.length >= 3) {
    showToast('L\u00edmite alcanzado', 'Solo puedes agregar 3 habilidades');
    return;
  }
  if (habilidadesLista.includes(valor)) {
    showToast('Duplicada', 'Esta habilidad ya fue agregada');
    return;
  }
  habilidadesLista.push(valor);
  input.value = '';
  renderHabilidadesTags();
}

function quitarHabilidad(index) {
  habilidadesLista.splice(index, 1);
  renderHabilidadesTags();
}

function renderHabilidadesTags() {
  const container = document.getElementById('apli-habilidades-tags');
  const hidden = document.getElementById('apli-habilidades');
  const count = document.getElementById('apli-habilidades-count');
  if (!container) return;

  container.innerHTML = habilidadesLista.map((h, i) => `
    <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(0,121,64,.1);color:var(--primary);padding:6px 12px;border-radius:999px;font-size:13px;font-weight:600;">
      ${escapeHtml(h)}
      <span onclick="quitarHabilidad(${i})" style="cursor:pointer;font-size:16px;line-height:1;">\u00d7</span>
    </span>
  `).join('');

  if (hidden) hidden.value = habilidadesLista.join(', ');
  if (count) count.textContent = habilidadesLista.length;

  const input = document.getElementById('apli-habilidad-input');
  if (input) input.disabled = habilidadesLista.length >= 3;
}

function limpiarHabilidadesTags() {
  habilidadesLista = [];
  renderHabilidadesTags();
}

function setupFormAplicar() {
  const formAplicar = document.getElementById('form-aplicar');
  if (formAplicar) {
    formAplicar.addEventListener('submit', async (e) => {
      e.preventDefault();

      let curriculum = null;
      try {
        curriculum = await leerArchivoCV(document.getElementById('apli-cv'));
      } catch (err) {
        showToast('Error en CV', err.message);
        return;
      }

      var vacanteIdVal = parseInt(document.getElementById('aplicar-vacante-id').value);
      var vacanteApp = vacantes.find(function(v) { return v.id === vacanteIdVal; });
      if (vacanteApp && vacanteApp.solicitarCV && !curriculum) {
        showToast('CV requerido', 'Esta vacante requiere que adjuntes tu Curriculum Vitae');
        return;
      }

      const telefono = document.getElementById('apli-telefono').value;
      if (!/^\d{10}$/.test(telefono)) {
        showToast('Tel\u00e9fono inv\u00e1lido', 'Ingresa exactamente 10 d\u00edgitos');
        return;
      }

      const candidato = {
        id: Date.now(),
        vacanteId: parseInt(document.getElementById('aplicar-vacante-id').value),
        nombre: document.getElementById('apli-nombre').value,
        apellidos: document.getElementById('apli-apellidos').value,
        email: document.getElementById('apli-email').value,
        telefono: telefono,
        fechaNacimiento: fechaDDMMAAAAaISO(document.getElementById('apli-fecha-nacimiento').value) || document.getElementById('apli-fecha-nacimiento').value,
        ciudad: document.getElementById('apli-ciudad').value,
        experiencia: document.getElementById('apli-experiencia').value,
        ultimaEmpresa: document.getElementById('apli-ultima-empresa').value,
        ultimoPuesto: document.getElementById('apli-ultimo-puesto').value,
        habilidades: document.getElementById('apli-habilidades').value,
        escolaridad: document.getElementById('apli-escolaridad').value,
        carrera: document.getElementById('apli-carrera').value,
        curriculum: curriculum,
        etapa: 'aplicado',
        fechaAplicacion: new Date().toISOString().split('T')[0],
        codigoSeguimiento: generarCodigoSeguimiento(),
        comentariosPublicos: [],
        comentariosInternos: []
      };

      candidatos.push(candidato);

      try {
        saveData();
      } catch (err) {
        candidatos.pop();
        if (err.name === 'QuotaExceededError' || err.code === 22) {
          showToast('Error de almacenamiento', 'No hay espacio suficiente. Intenta con un archivo m\u00e1s peque\u00f1o.');
        } else {
          showToast('Error', 'No se pudo guardar la aplicaci\u00f3n.');
        }
        return;
      }

      const vacante = vacantes.find(v => v.id === candidato.vacanteId);
      const nombreVacante = vacante ? vacante.titulo : 'vacante';
      crearNotificacion('rh', `Nuevo candidato: ${candidato.nombre} ${candidato.apellidos} aplic\u00f3 a ${nombreVacante}`, { vista: 'gestion-candidatos', candidatoId: candidato.id });

      closeModal('aplicar');
      e.target.reset();
      limpiarHabilidadesTags();

      __pendingCodigoSeguimiento = candidato.codigoSeguimiento;

      const celTitle = document.querySelector('.celebration-title');
      const celSub = document.querySelector('.celebration-sub');
      if (celTitle) celTitle.textContent = '\u00a1Felicidades!';
      if (celSub) celSub.textContent = `Te has postulado con \u00e9xito a la vacante ${nombreVacante}, nos pondremos en contacto contigo muy pronto.`;
      celebrate();

      renderCandidatosTable();
    });
  }
}

// Form: Agendar Entrevista
function setupFormEntrevista() {
  const formEntrevista = document.getElementById('form-agendar-entrevista');
  if (formEntrevista) {
    formEntrevista.addEventListener('submit', (e) => {
      e.preventDefault();

      const candidatoId = parseInt(document.getElementById('entrevista-candidato-id').value);
      const tipo = document.getElementById('entrevista-tipo').value;
      const reclutadoraId = document.getElementById('entrevista-reclutadora').value || null;
      const fechaISO = fechaDDMMAAAAaISO(document.getElementById('entrevista-fecha').value) || document.getElementById('entrevista-fecha').value;
      const hora = document.getElementById('entrevista-hora').value;
      const duracion = document.getElementById('entrevista-duracion').value;

      // Validar conflicto de horario para entrevistas RH
      if (tipo === 'rh' && reclutadoraId) {
        const conflicto = detectarConflictoHorario(reclutadoraId, fechaISO, hora, parseInt(duracion));
        if (conflicto) {
          showToast('Conflicto de horario', conflicto.reclutadoraNombre + ' ya tiene entrevista de ' + conflicto.horaInicio + ' a ' + conflicto.horaFin);
          return;
        }
      }

      const lugarClave = document.getElementById('entrevista-lugar').value;
      const linkReunion = document.getElementById('entrevista-link').value.trim();

      // Validar enlace obligatorio para reunión en línea
      if (lugarClave === 'online' && !linkReunion) {
        showToast('Enlace requerido', 'Debes ingresar el enlace de la reunión en línea');
        document.getElementById('entrevista-link').focus();
        return;
      }

      const lugarTexto = lugarClave === 'online'
        ? 'Reunión en línea'
        : (UBICACION_NOMBRES[lugarClave] || lugarClave);

      const entrevista = {
        id: Date.now(),
        candidatoId: candidatoId,
        tipo: tipo,
        fecha: fechaISO,
        hora: hora,
        duracion: duracion,
        entrevistador: document.getElementById('entrevista-entrevistador').value,
        reclutadoraId: reclutadoraId,
        lugar: lugarTexto,
        lugarClave: lugarClave,
        direccion: lugarClave !== 'online' ? (DIRECCIONES[lugarClave] || '') : '',
        linkReunion: lugarClave === 'online' ? linkReunion : '',
        notas: document.getElementById('entrevista-notas').value
      };

      entrevistas.push(entrevista);

      const candidato = candidatos.find(c => c.id === candidatoId);
      if (candidato) {
        if (tipo === 'rh') {
          candidato.etapa = 'entrevista-rh';
        } else if (tipo === 'jefe') {
          candidato.etapa = 'entrevista-jefe';
        }
      }

      saveData();

      showToast('Entrevista agendada', 'La entrevista ha sido programada exitosamente');
      closeModal('agendar-entrevista');
      document.getElementById('entrevista-disponibilidad-wrapper').style.display = 'none';
      document.getElementById('entrevista-link-wrapper').style.display = 'none';
      document.getElementById('entrevista-lugar-direccion').style.display = 'none';
      e.target.reset();
      renderCandidatosTable();
    });
  }

  // Al cambiar duración, actualizar bloque visual de selección
  var durInput = document.getElementById('entrevista-duracion');
  if (durInput) {
    durInput.addEventListener('input', function() {
      var fecha = fechaDDMMAAAAaISO(document.getElementById('entrevista-fecha').value) || document.getElementById('entrevista-fecha').value;
      var hora = document.getElementById('entrevista-hora').value;
      if (fecha && hora) miniCalMostrarSeleccion(fecha, hora);
    });
  }
}

// Form: Alta de Empleado
function setupFormAltaEmpleado() {
  const formAlta = document.getElementById('form-alta-empleado');
  if (formAlta) {
    formAlta.addEventListener('submit', (e) => {
      e.preventDefault();

      const totalDocs = 6;
      const completedDocs = ['doc-ine', 'doc-nss', 'doc-curp', 'doc-rfc', 'doc-comprobante', 'doc-estudios']
        .filter(id => document.getElementById(id).checked).length;

      if (completedDocs < totalDocs) {
        showToast('Documentos incompletos', 'Debes marcar todos los documentos como entregados');
        return;
      }

      const candidatoId = parseInt(document.getElementById('alta-candidato-id').value);
      const candidato = candidatos.find(c => c.id === candidatoId);

      if (candidato) {
        candidato.etapa = 'contratado';
        candidato.datosLaborales = {
          puesto: document.getElementById('alta-puesto').value,
          jefe: document.getElementById('alta-jefe').value,
          fechaIngreso: fechaDDMMAAAAaISO(document.getElementById('alta-fecha-ingreso').value) || document.getElementById('alta-fecha-ingreso').value,
          salario: document.getElementById('alta-salario').value,
          horaEntrada: document.getElementById('alta-hora-entrada').value,
          horaSalida: document.getElementById('alta-hora-salida').value
        };

        saveData();

        showToast('\u00a1Empleado contratado!', 'El alta se ha completado exitosamente');
        celebrate();

        setTimeout(() => {
          closeModal('alta-empleado');
          e.target.reset();
          renderCandidatosTable();
        }, 2800);
      }
    });
  }
}

// ==================== PROGRESS TRACKING ====================
const docCheckboxes = ['doc-ine', 'doc-nss', 'doc-curp', 'doc-rfc', 'doc-comprobante', 'doc-estudios'];

function updateAltaProgress() {
  const total = docCheckboxes.length;
  const completed = docCheckboxes.filter(id => document.getElementById(id).checked).length;
  const missing = total - completed;
  const pct = Math.round((completed / total) * 100);

  document.getElementById('alta-progress-number').textContent = `${pct}%`;
  document.getElementById('alta-progress-fill').style.width = `${pct}%`;
  document.getElementById('alta-progress-delivered').textContent = `${completed}/${total} entregados`;
  document.getElementById('alta-progress-missing').textContent = `${missing} faltantes`;

  if (pct === 100 && !__celebrated) {
    __celebrated = true;
    celebrate();
  }
  if (pct < 100) {
    __celebrated = false;
  }
}

docCheckboxes.forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('change', updateAltaProgress);
});

// ==================== SEGUIMIENTO PÚBLICO ====================
function consultarPostulacion() {
  const input = document.getElementById('input-codigo');
  if (!input) return;
  const codigo = input.value.trim();
  if (!/^\d{6}$/.test(codigo)) {
    showToast('Código inválido', 'Ingresa un código de 6 dígitos');
    return;
  }
  const candidato = candidatos.find(c => c.codigoSeguimiento === codigo);
  if (!candidato) {
    showToast('No encontrado', 'No se encontró ninguna postulación con ese código');
    return;
  }
  renderSeguimientoPublico(candidato);
}

function renderSeguimientoPublico(candidato) {
  const vacante = vacantes.find(v => v.id === candidato.vacanteId);
  const etapaActual = getEtapaNumero(candidato.etapa);

  document.getElementById('seguimiento-buscar').style.display = 'none';
  const resultado = document.getElementById('seguimiento-resultado');
  resultado.style.display = 'block';

  let mensajeEstado = '';
  if (candidato.etapa === 'contratado') {
    mensajeEstado = `
      <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:10px;padding:16px 20px;margin-top:16px;text-align:center;">
        <p style="margin:0;font-size:18px;font-weight:800;color:#166534;">¡Felicidades, has sido seleccionado!</p>
        <p style="margin:6px 0 0;font-size:14px;color:#15803d;">Nos pondremos en contacto contigo para los siguientes pasos.</p>
      </div>`;
  } else if (candidato.etapa === 'rechazado') {
    mensajeEstado = `
      <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:16px 20px;margin-top:16px;text-align:center;">
        <p style="margin:0;font-size:16px;font-weight:800;color:#991b1b;">Tu proceso ha concluido</p>
        <p style="margin:6px 0 0;font-size:14px;color:#7f1d1d;">Agradecemos tu interés. Te invitamos a seguir revisando nuestras vacantes.</p>
      </div>`;
  }

  const comentarios = (candidato.comentariosPublicos || []).slice().reverse();
  const comentariosHtml = comentarios.length === 0
    ? `<div style="text-align:center;padding:24px 0;color:var(--muted);">
        <p style="font-size:14px;margin:0;">Aún no hay actualizaciones.</p>
        <p style="font-size:12px;margin:4px 0 0;">Te notificaremos cuando haya novedades en tu proceso.</p>
      </div>`
    : comentarios.map(c => `
        <div style="border:1px solid var(--border);border-radius:8px;padding:12px 14px;margin-bottom:8px;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <span style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);font-weight:700;">${escapeHtml(c.etapa || '')}</span>
            <span style="font-size:11px;color:var(--muted);">${formatFecha(c.fecha)}</span>
          </div>
          <p style="margin:0;font-size:14px;">${escapeHtml(c.texto)}</p>
        </div>
      `).join('');

  resultado.innerHTML = `
    <div style="background:rgba(0,121,64,.07);border:1px solid rgba(0,121,64,.2);border-radius:10px;padding:18px 22px;margin-bottom:20px;">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;">
        <div>
          <p style="margin:0;font-size:20px;font-weight:900;color:var(--primary);">${escapeHtml(candidato.nombre)} ${escapeHtml(candidato.apellidos)}</p>
          <p style="margin:4px 0 0;font-size:14px;color:var(--muted);">${vacante ? escapeHtml(vacante.titulo) : 'Vacante'} &middot; Postulado el ${formatFecha(candidato.fechaAplicacion)}</p>
        </div>
        <span class="badge badge-${getBadgeClass(candidato.etapa)}" style="white-space:nowrap;">${getEtapaLabel(candidato.etapa).toUpperCase()}</span>
      </div>
    </div>

    ${mensajeEstado}

    <div class="card" style="margin-top:20px;">
      <div class="card-header">
        <h2>Progreso de Tu Proceso</h2>
      </div>
      <div class="proceso-steps">
        ${renderProcesoSteps(etapaActual, candidato)}
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Actualizaciones del Proceso</h2>
      </div>
      ${comentariosHtml}
    </div>

    <div style="text-align:center;margin-top:20px;">
      <button class="btn btn-ghost" onclick="volverBusqueda()">Consultar otro código</button>
      <a href="${(window.__VACANTES_CONFIG && window.__VACANTES_CONFIG.portalUrl) || 'unetealequipo.html'}" class="btn btn-primary" style="text-decoration:none;margin-left:8px;">Ver vacantes</a>
    </div>
  `;
}

function volverBusqueda() {
  document.getElementById('seguimiento-buscar').style.display = '';
  document.getElementById('seguimiento-resultado').style.display = 'none';
  document.getElementById('seguimiento-resultado').innerHTML = '';
  const input = document.getElementById('input-codigo');
  if (input) input.value = '';
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
  // Setup form listeners
  setupFormListeners();
  setupFormAplicar();
  setupFormEntrevista();
  setupFormAltaEmpleado();
  setupFormsSolicitud();
  setupFormCompletarVacante();
  initCalRecDragAndDrop();

  // Demo vacantes (20 vacantes repartidas en areas + candidatos en distintas etapas)
  // Force re-seed if old demo data or missing etapaRechazo
  // Asignar codigo automaticamente a vacantes existentes que no tengan
  var vacSinCodigo = vacantes.filter(function(v) { return !v.codigo; });
  if (vacSinCodigo.length > 0) {
    var maxNum = 0;
    vacantes.forEach(function(v) {
      if (v.codigo) {
        var n = parseInt(v.codigo.replace('VAC-', ''), 10);
        if (n > maxNum) maxNum = n;
      }
    });
    vacSinCodigo.forEach(function(v) {
      maxNum++;
      v.codigo = 'VAC-' + String(maxNum).padStart(4, '0');
    });
    localStorage.setItem('vacantes', JSON.stringify(vacantes));
  }

  // Migración: asignar reclutadoraId a entrevistas RH sin asignar
  var entSinRec = entrevistas.filter(function(e) { return e.tipo === 'rh' && !e.reclutadoraId; });
  if (entSinRec.length > 0) {
    entSinRec.forEach(function(e, i) {
      e.reclutadoraId = RECLUTADORAS[i % RECLUTADORAS.length].id;
    });
    localStorage.setItem('entrevistas', JSON.stringify(entrevistas));
  }

  // Migración: asignar reclutadoraId a vacantes abiertas sin asignar
  var vacSinRec = vacantes.filter(function(v) { return v.estado === 'abierta' && !v.reclutadoraId; });
  if (vacSinRec.length > 0) {
    vacSinRec.forEach(function(v, i) {
      v.reclutadoraId = RECLUTADORAS[i % RECLUTADORAS.length].id;
    });
    localStorage.setItem('vacantes', JSON.stringify(vacantes));
  }

  // Migración: asignar solicitarCV a vacantes existentes que no lo tengan
  var vacSinCV = vacantes.filter(function(v) { return typeof v.solicitarCV === 'undefined'; });
  if (vacSinCV.length > 0) {
    vacSinCV.forEach(function(v) {
      v.solicitarCV = true;
    });
    localStorage.setItem('vacantes', JSON.stringify(vacantes));
  }

  var needsReseed = vacantes.length < 3 || candidatos.some(function(c) { return c.etapa === 'rechazado' && !c.etapaRechazo; }) || !candidatos.some(function(c) { return c.id >= 9001 && c.id <= 9010; });
  if (needsReseed) {
    vacantes = [];
    candidatos = [];
    entrevistas = [];
    localStorage.removeItem('vacantes');
    localStorage.removeItem('candidatos');
    localStorage.removeItem('entrevistas');
  }
  if (vacantes.length === 0) {
    vacantes = [
      { id: 1, codigo: 'VAC-0001', titulo: 'Desarrollador Full Stack', departamento: 'IT', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025, Col Americana, Lafayette, 44130 Guadalajara, Jal.', tipo: 'Tiempo Completo', salario: '$25,000 - $35,000', descripcion: 'Desarrollo web con React y Node.js.', requisitos: '3 años de experiencia, Git, agiles', estado: 'abierta', fechaCreacion: '2025-10-15' },
      { id: 2, codigo: 'VAC-0002', titulo: 'Gerente de Ventas', departamento: 'Ventas', ubicacion: 'Planta 2 Artes', ubicacionClave: 'planta2', direccion: 'C. Artes 2767, San Rafael, 44810 Guadalajara, Jal.', tipo: 'Tiempo Completo', salario: '$30,000 - $45,000', descripcion: 'Manejo de equipos de ventas y cumplimiento de objetivos.', requisitos: '5 años, liderazgo', estado: 'abierta', fechaCreacion: '2025-11-01' },
      { id: 3, codigo: 'VAC-0003', titulo: 'Analista de Datos', departamento: 'IT', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$22,000 - $30,000', descripcion: 'Analisis de datos con Python y SQL.', requisitos: '2 años en analisis de datos', estado: 'abierta', fechaCreacion: '2025-11-10' },
      { id: 4, codigo: 'VAC-0004', titulo: 'Ejecutivo de Ventas', departamento: 'Ventas', ubicacion: 'Planta 1 Tonala', ubicacionClave: 'planta1', direccion: 'Av. Tonala #1234', tipo: 'Tiempo Completo', salario: '$15,000 - $22,000', descripcion: 'Prospeccion y cierre de ventas B2B.', requisitos: '2 años en ventas', estado: 'abierta', fechaCreacion: '2025-11-20' },
      { id: 5, codigo: 'VAC-0005', titulo: 'Disenador Grafico', departamento: 'Marketing', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$18,000 - $25,000', descripcion: 'Diseno de material grafico para campanas.', requisitos: 'Adobe Creative Suite', estado: 'abierta', fechaCreacion: '2025-12-01' },
      { id: 6, codigo: 'VAC-0006', titulo: 'Supervisor de Produccion', departamento: 'Operaciones', ubicacion: 'Planta 1 Tonala', ubicacionClave: 'planta1', direccion: 'Av. Tonala #1234', tipo: 'Tiempo Completo', salario: '$20,000 - $28,000', descripcion: 'Supervision de lineas de produccion.', requisitos: '3 años en manufactura', estado: 'abierta', fechaCreacion: '2025-12-05' },
      { id: 7, codigo: 'VAC-0007', titulo: 'Contador General', departamento: 'Finanzas', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$22,000 - $30,000', descripcion: 'Contabilidad general y estados financieros.', requisitos: 'Lic. Contaduria, 3 años', estado: 'abierta', fechaCreacion: '2025-12-10' },
      { id: 8, codigo: 'VAC-0008', titulo: 'Coordinador de RRHH', departamento: 'RRHH', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$20,000 - $28,000', descripcion: 'Coordinacion de procesos de reclutamiento y capacitacion.', requisitos: 'Lic. Psicologia o afin', estado: 'abierta', fechaCreacion: '2025-12-15' },
      { id: 9, codigo: 'VAC-0009', titulo: 'Community Manager', departamento: 'Marketing', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$15,000 - $20,000', descripcion: 'Gestion de redes sociales y contenido digital.', requisitos: '1 año en redes sociales', estado: 'abierta', fechaCreacion: '2026-01-05' },
      { id: 10, codigo: 'VAC-0010', titulo: 'Ingeniero de Calidad', departamento: 'Operaciones', ubicacion: 'Planta 1 Tonala', ubicacionClave: 'planta1', direccion: 'Av. Tonala #1234', tipo: 'Tiempo Completo', salario: '$18,000 - $25,000', descripcion: 'Control de calidad en lineas de produccion.', requisitos: 'ISO 9001, Six Sigma', estado: 'abierta', fechaCreacion: '2026-01-10' },
      { id: 11, codigo: 'VAC-0011', titulo: 'Soporte Tecnico', departamento: 'IT', ubicacion: 'Planta 1 Tonala', ubicacionClave: 'planta1', direccion: 'Av. Tonala #1234', tipo: 'Tiempo Completo', salario: '$14,000 - $18,000', descripcion: 'Soporte a usuarios y mantenimiento de equipos.', requisitos: 'Tecnico en sistemas', estado: 'abierta', fechaCreacion: '2026-01-15' },
      { id: 12, codigo: 'VAC-0012', titulo: 'Auxiliar Contable', departamento: 'Finanzas', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Medio Tiempo', salario: '$10,000 - $14,000', descripcion: 'Apoyo en registro contable y conciliaciones.', requisitos: 'Estudiante de Contaduria', estado: 'abierta', fechaCreacion: '2026-01-20' },
      { id: 13, codigo: 'VAC-0013', titulo: 'Jefe de Almacen', departamento: 'Operaciones', ubicacion: 'CEDIS Tonala', ubicacionClave: 'cedis', direccion: 'Av. Tonala #5678', tipo: 'Tiempo Completo', salario: '$18,000 - $24,000', descripcion: 'Gestion de inventarios y despacho de mercancia.', requisitos: 'Ing. Industrial, 2 años', estado: 'abierta', fechaCreacion: '2026-01-25' },
      { id: 14, codigo: 'VAC-0014', titulo: 'Asistente de Direccion', departamento: 'Administracion', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$16,000 - $22,000', descripcion: 'Asistencia ejecutiva a la direccion general.', requisitos: 'Lic. Administracion, ingles avanzado', estado: 'abierta', fechaCreacion: '2026-02-01' },
      { id: 15, codigo: 'VAC-0015', titulo: 'Analista de Nominas', departamento: 'RRHH', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$17,000 - $23,000', descripcion: 'Calculo de nominas y prestaciones.', requisitos: 'Experiencia con IMSS, Infonavit', estado: 'abierta', fechaCreacion: '2026-02-05' },
      { id: 16, codigo: 'VAC-0016', titulo: 'Operador de Produccion', departamento: 'Operaciones', ubicacion: 'Planta 1 Tonala', ubicacionClave: 'planta1', direccion: 'Av. Tonala #1234', tipo: 'Tiempo Completo', salario: '$9,000 - $12,000', descripcion: 'Operacion de maquinaria en lineas de produccion.', requisitos: 'Secundaria terminada', estado: 'cerrada', fechaCreacion: '2025-09-15' },
      { id: 17, codigo: 'VAC-0017', titulo: 'Ejecutivo de Cobranza', departamento: 'Finanzas', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$14,000 - $18,000', descripcion: 'Gestion de cartera de cobranza.', requisitos: '1 año en cobranza', estado: 'cerrada', fechaCreacion: '2025-10-01' },
      { id: 18, codigo: 'VAC-0018', titulo: 'Recepcionista', departamento: 'Administracion', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$10,000 - $13,000', descripcion: 'Atencion al publico y manejo de conmutador.', requisitos: 'Preparatoria terminada', estado: 'cerrada', fechaCreacion: '2025-09-01' },
      { id: 19, codigo: 'VAC-0019', titulo: 'Coordinador de Logistica', departamento: 'Operaciones', ubicacion: 'CEDIS Tonala', ubicacionClave: 'cedis', direccion: 'Av. Tonala #5678', tipo: 'Tiempo Completo', salario: '$22,000 - $30,000', descripcion: 'Coordinacion de rutas de distribucion.', requisitos: 'Ing. Industrial, 3 años', estado: 'abierta', fechaCreacion: '2026-02-10' },
      { id: 20, codigo: 'VAC-0020', titulo: 'Especialista SEO/SEM', departamento: 'Marketing', ubicacion: 'Corporativo Vallarta', ubicacionClave: 'corporativo', direccion: 'Av. Ignacio L Vallarta 2025', tipo: 'Tiempo Completo', salario: '$20,000 - $28,000', descripcion: 'Optimizacion de campanas en buscadores.', requisitos: 'Google Ads, Analytics, 2 años', estado: 'abierta', fechaCreacion: '2026-02-15' }
    ];

    // Set solicitarCV for seed vacantes (false solo para puestos operativos)
    vacantes.forEach(function(v) {
      v.solicitarCV = (v.id !== 16 && v.id !== 18);
      v.mostrarSalario = true;
    });

    // Seed candidatos (60 candidatos repartidos en distintas etapas y vacantes)
    var etapas = ['aplicado','entrevista-rh','primer-filtro','entrevista-jefe','revision-medica','psicometrico','referencias','documentos','contratado','rechazado'];
    var nombres = ['Juan','Maria','Pedro','Ana','Carlos','Laura','Miguel','Sofia','Diego','Carmen','Fernando','Patricia','Ricardo','Gabriela','Andres','Monica','Luis','Elena','Jorge','Valeria','Oscar','Daniela','Roberto','Paola','Eduardo','Fernanda','Raul','Alejandra','Manuel','Isabella'];
    var apellidos = ['Garcia','Martinez','Lopez','Hernandez','Gonzalez','Rodriguez','Perez','Sanchez','Ramirez','Torres','Flores','Rivera','Gomez','Diaz','Cruz','Morales','Reyes','Ortiz','Gutierrez','Ruiz','Mendoza','Aguilar','Castillo','Vargas','Rojas'];

    var seedCandidatos = [];
    var candId = 1000;
    var meses = ['2025-10','2025-11','2025-12','2026-01','2026-02'];

    for (var vi = 0; vi < vacantes.length; vi++) {
      var v = vacantes[vi];
      var numCands = vi < 6 ? 5 : (vi < 12 ? 3 : 2);
      for (var ci = 0; ci < numCands; ci++) {
        candId++;
        var etapaIdx = (vi + ci) % etapas.length;
        var mesIdx = (vi + ci) % meses.length;
        var dia = String(((vi * 3 + ci * 7) % 28) + 1).padStart(2, '0');
        var nomIdx = (candId) % nombres.length;
        var apIdx = (candId + 7) % apellidos.length;

        var candidatoSeed = {
          id: candId,
          vacanteId: v.id,
          nombre: nombres[nomIdx],
          apellidos: apellidos[apIdx],
          email: nombres[nomIdx].toLowerCase() + '.' + apellidos[apIdx].toLowerCase() + '@email.com',
          telefono: '33' + String(10000000 + candId).slice(-8),
          fechaNacimiento: '199' + (candId % 9) + '-0' + ((candId % 9) + 1) + '-15',
          ciudad: 'Guadalajara',
          experiencia: String((candId % 5) + 1) + ' años',
          ultimaEmpresa: 'Empresa ' + String.fromCharCode(65 + (candId % 26)),
          ultimoPuesto: 'Puesto anterior',
          habilidades: 'Habilidad A, Habilidad B',
          escolaridad: ci % 2 === 0 ? 'Licenciatura' : 'Ingenieria',
          carrera: 'Carrera profesional',
          curriculum: null,
          etapa: etapas[etapaIdx],
          fechaAplicacion: meses[mesIdx] + '-' + dia,
          codigoSeguimiento: String(100000 + candId),
          comentariosPublicos: [],
          comentariosInternos: []
        };
        if (etapas[etapaIdx] === 'rechazado') {
          candidatoSeed.etapaRechazo = (candId % 8) + 1;
          candidatoSeed.motivoRechazo = 'No cumple con los requisitos del puesto';
        }
        seedCandidatos.push(candidatoSeed);
      }
    }
    // 10 candidatos adicionales recién postulados (etapa: aplicado)
    var postulados = [
      { nombre: 'Alejandro', apellidos: 'Navarro Ríos', email: 'alejandro.navarro@gmail.com', telefono: '3312456701', fechaNacimiento: '1995-03-22', ciudad: 'Guadalajara', experiencia: '3 años', ultimaEmpresa: 'Softtek', ultimoPuesto: 'Desarrollador Jr', habilidades: 'JavaScript, React, SQL', escolaridad: 'Ingenieria', carrera: 'Ing. en Computación', vacanteId: 1 },
      { nombre: 'Mariana', apellidos: 'Estrada Solís', email: 'mariana.estrada@outlook.com', telefono: '3318765432', fechaNacimiento: '1997-07-10', ciudad: 'Zapopan', experiencia: '2 años', ultimaEmpresa: 'Liverpool', ultimoPuesto: 'Ejecutiva de Ventas', habilidades: 'Ventas B2B, CRM, Negociación', escolaridad: 'Licenciatura', carrera: 'Lic. en Mercadotecnia', vacanteId: 2 },
      { nombre: 'Iván', apellidos: 'Córdova Delgado', email: 'ivan.cordova@hotmail.com', telefono: '3325678901', fechaNacimiento: '1993-11-05', ciudad: 'Tlaquepaque', experiencia: '4 años', ultimaEmpresa: 'HSBC', ultimoPuesto: 'Analista BI', habilidades: 'Python, Power BI, SQL', escolaridad: 'Licenciatura', carrera: 'Lic. en Actuaría', vacanteId: 3 },
      { nombre: 'Paulina', apellidos: 'Becerra Meza', email: 'paulina.becerra@gmail.com', telefono: '3331234567', fechaNacimiento: '1998-01-18', ciudad: 'Guadalajara', experiencia: '1 año', ultimaEmpresa: 'Freelance', ultimoPuesto: 'Diseñadora', habilidades: 'Photoshop, Illustrator, Figma', escolaridad: 'Licenciatura', carrera: 'Lic. en Diseño Gráfico', vacanteId: 5 },
      { nombre: 'Héctor', apellidos: 'Salazar Orozco', email: 'hector.salazar@yahoo.com', telefono: '3347890123', fechaNacimiento: '1990-05-30', ciudad: 'Tonalá', experiencia: '6 años', ultimaEmpresa: 'Flextronics', ultimoPuesto: 'Supervisor de Línea', habilidades: 'Lean Manufacturing, 5S, Liderazgo', escolaridad: 'Ingenieria', carrera: 'Ing. Industrial', vacanteId: 6 },
      { nombre: 'Diana', apellidos: 'Quintero Luna', email: 'diana.quintero@gmail.com', telefono: '3354321098', fechaNacimiento: '1994-09-12', ciudad: 'Zapopan', experiencia: '3 años', ultimaEmpresa: 'Deloitte', ultimoPuesto: 'Contador Sr', habilidades: 'SAP, Nóminas, IMSS', escolaridad: 'Licenciatura', carrera: 'Lic. en Contaduría', vacanteId: 7 },
      { nombre: 'Sebastián', apellidos: 'Ochoa Ibarra', email: 'sebastian.ochoa@outlook.com', telefono: '3367654321', fechaNacimiento: '1996-12-03', ciudad: 'Guadalajara', experiencia: '2 años', ultimaEmpresa: 'IBM', ultimoPuesto: 'Soporte N2', habilidades: 'Redes, Windows Server, ITIL', escolaridad: 'Ingenieria', carrera: 'Ing. en Sistemas', vacanteId: 11 },
      { nombre: 'Renata', apellidos: 'Campos Herrera', email: 'renata.campos@gmail.com', telefono: '3370987654', fechaNacimiento: '1999-04-25', ciudad: 'Tlajomulco', experiencia: '1 año', ultimaEmpresa: 'Agencia Digital MX', ultimoPuesto: 'Community Manager Jr', habilidades: 'Redes sociales, Canva, Copywriting', escolaridad: 'Licenciatura', carrera: 'Lic. en Comunicación', vacanteId: 9 },
      { nombre: 'Emilio', apellidos: 'Villarreal Ponce', email: 'emilio.villarreal@hotmail.com', telefono: '3383210987', fechaNacimiento: '1991-08-14', ciudad: 'Tonalá', experiencia: '5 años', ultimaEmpresa: 'FedEx', ultimoPuesto: 'Coordinador Logístico', habilidades: 'WMS, Rutas, Inventarios', escolaridad: 'Ingenieria', carrera: 'Ing. Industrial', vacanteId: 19 },
      { nombre: 'Ximena', apellidos: 'Treviño Ávila', email: 'ximena.trevino@gmail.com', telefono: '3396543210', fechaNacimiento: '1997-02-08', ciudad: 'Guadalajara', experiencia: '2 años', ultimaEmpresa: 'Tequila Don Julio', ultimoPuesto: 'Analista de Nóminas', habilidades: 'NOI, Excel avanzado, IMSS', escolaridad: 'Licenciatura', carrera: 'Lic. en Administración', vacanteId: 15 }
    ];
    var seedCvData = 'data:application/pdf;base64,JVBERi0xLjAKMSAwIG9iajw8L1R5cGUvQ2F0YWxvZy9QYWdlcyAyIDAgUj4+ZW5kb2JqCjIgMCBvYmo8PC9UeXBlL1BhZ2VzL0tpZHNbMyAwIFJdL0NvdW50IDE+PmVuZG9iagozIDAgb2JqPDwvVHlwZS9QYWdlL01lZGlhQm94WzAgMCA2MTIgNzkyXS9QYXJlbnQgMiAwIFIvUmVzb3VyY2VzPDw+Pj4+ZW5kb2JqCnhyZWYKMCA0CjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAwMDAwOSAwMDAwMCBuIAowMDAwMDAwMDU4IDAwMDAwIG4gCjAwMDAwMDAxMTUgMDAwMDAgbiAKdHJhaWxlcjw8L1NpemUgNC9Sb290IDEgMCBSPj4Kc3RhcnR4cmVmCjIwNgolJUVPRg==';
    var postId = 9000;
    postulados.forEach(function(p) {
      postId++;
      var dia = String(((postId * 3) % 28) + 1).padStart(2, '0');
      var cvNombre = 'CV_' + p.nombre + '_' + p.apellidos.split(' ')[0] + '.pdf';
      seedCandidatos.push({
        id: postId,
        vacanteId: p.vacanteId,
        nombre: p.nombre,
        apellidos: p.apellidos,
        email: p.email,
        telefono: p.telefono,
        fechaNacimiento: p.fechaNacimiento,
        ciudad: p.ciudad,
        experiencia: p.experiencia,
        ultimaEmpresa: p.ultimaEmpresa,
        ultimoPuesto: p.ultimoPuesto,
        habilidades: p.habilidades,
        escolaridad: p.escolaridad,
        carrera: p.carrera,
        curriculum: { nombre: cvNombre, tipo: 'application/pdf', data: seedCvData },
        etapa: 'aplicado',
        fechaAplicacion: '2026-02-' + dia,
        codigoSeguimiento: String(200000 + postId),
        comentariosPublicos: [],
        comentariosInternos: []
      });
    });

    candidatos = seedCandidatos;

    // Seed entrevistas (para candidatos en etapas de entrevista)
    var seedEntrevistas = [];
    var entId = 5000;
    var horasVariadas = ['09:00','09:30','10:00','10:30','11:00','11:30','13:00','14:00','14:30','15:00','15:30','16:00'];
    var horaIdx = 0;
    var hoy = new Date();
    var lunesSemana = getMonday(hoy);
    candidatos.forEach(function(c) {
      if (c.etapa === 'entrevista-rh' || c.etapa === 'entrevista-jefe') {
        entId++;
        var esRH = c.etapa === 'entrevista-rh';
        var recIdx = horaIdx % RECLUTADORAS.length;
        var diaOffset = horaIdx % 5;
        var fechaEnt = new Date(lunesSemana);
        fechaEnt.setDate(lunesSemana.getDate() + diaOffset);
        var fechaISO = fechaEnt.toISOString().split('T')[0];
        seedEntrevistas.push({
          id: entId,
          candidatoId: c.id,
          tipo: esRH ? 'rh' : 'jefe',
          fecha: fechaISO,
          hora: horasVariadas[horaIdx % horasVariadas.length],
          duracion: '60',
          reclutadoraId: esRH ? RECLUTADORAS[recIdx].id : null,
          entrevistador: esRH ? RECLUTADORAS[recIdx].nombre : 'Jefe de Área',
          lugar: 'Sala de juntas',
          notas: ''
        });
        horaIdx++;
      }
    });
    entrevistas = seedEntrevistas;

    saveData();
  }

  // Migración: agregar ubicacionClave y direccion a vacantes existentes que no los tengan
  vacantes.forEach(v => {
    if (!v.ubicacionClave && !v.direccion) {
      if (v.id === 1) {
        v.ubicacion = 'Corporativo Vallarta';
        v.ubicacionClave = 'corporativo';
        v.direccion = 'Av. Ignacio L Vallarta 2025, Col Americana, Lafayette, 44130 Guadalajara, Jal.';
      } else if (v.id === 2) {
        v.ubicacion = 'Planta 2 Artes';
        v.ubicacionClave = 'planta2';
        v.direccion = 'C. Artes 2767, San Rafael, 44810 Guadalajara, Jal.';
      }
    }
  });
  saveData();

  // Demo solicitudes aprobadas por DO, pendientes de Finanzas
  if (!solicitudes.some(s => s.id === 1001)) {
    solicitudes.push(
      {
        id: 1001,
        titulo: 'Ingeniero de Calidad',
        departamento: 'Operaciones',
        justificacion: 'Se requiere reforzar el equipo de calidad por incremento en volumen de producción y nuevos clientes.',
        descripcionPuesto: 'Supervisar y ejecutar procesos de control de calidad en líneas de producción, asegurando el cumplimiento de estándares ISO 9001.',
        requisitosPuesto: 'Ingeniería Industrial o afín, 3+ años en control de calidad, conocimiento de normas ISO, Six Sigma deseable.',
        tipo: 'existente',
        cantidadVacantes: 2,
        puestoReferencia: 'Ingeniero de Calidad',
        solicitante: 'jefe-operaciones',
        fechaSolicitud: '2026-02-10',
        estado: 'aprobada-do',
        aprobacionDO: { estado: 'aprobada', comentario: 'Aprobado, el área de calidad necesita refuerzo urgente.', fecha: '2026-02-11' },
        aprobacionFinanzas: { estado: 'pendiente', comentario: '', fecha: '' },
        sueldoDesde: '18000',
        sueldoHasta: '25000',
        jornada: 'Tiempo completo',
        duracion: 'Por tiempo indeterminado',
        horario: { turno: 'L-V', dias: { Lunes: { entrada: '08:00', salida: '17:00' }, Martes: { entrada: '08:00', salida: '17:00' }, Miércoles: { entrada: '08:00', salida: '17:00' }, Jueves: { entrada: '08:00', salida: '17:00' }, Viernes: { entrada: '08:00', salida: '17:00' } } },
        ubicacion: 'planta1',
        direccion: 'Av. Tonalá #1234, Zona Industrial, Tonalá, Jalisco',
        actividades: 'Inspección de producto en proceso y terminado.\nElaboración de reportes de no conformidades.\nAuditorías internas de calidad.\nCapacitación al personal operativo en temas de calidad.',
        conocimientos: 'Normas ISO 9001:2015, herramientas estadísticas (SPC), metrología, uso de instrumentos de medición.',
        ofrecemos: 'Prestaciones superiores a las de ley, seguro de gastos médicos mayores, capacitación continua.',
        beneficios: 'Vales de despensa, fondo de ahorro, bono de productividad trimestral, comedor subsidiado.',
        vacanteId: null
      },
      {
        id: 1002,
        titulo: 'Ejecutivo de Ventas Corporativas',
        departamento: 'Ventas',
        justificacion: 'Apertura de nueva cartera de clientes corporativos en la zona Bajío requiere personal dedicado.',
        descripcionPuesto: 'Gestionar y desarrollar cartera de clientes corporativos, negociación de contratos y seguimiento postventa para asegurar la retención.',
        requisitosPuesto: 'Licenciatura en Administración, Marketing o afín, 2+ años en ventas B2B, manejo de CRM, disponibilidad para viajar.',
        tipo: 'nuevo',
        cantidadVacantes: 1,
        puestoReferencia: null,
        solicitante: 'jefe-ventas',
        fechaSolicitud: '2026-02-12',
        estado: 'aprobada-do',
        aprobacionDO: { estado: 'aprobada', comentario: 'Viable, la expansión al Bajío es prioridad estratégica.', fecha: '2026-02-13' },
        aprobacionFinanzas: { estado: 'pendiente', comentario: '', fecha: '' },
        sueldoDesde: '15000',
        sueldoHasta: '22000',
        jornada: 'Tiempo completo',
        duracion: 'Por tiempo indeterminado',
        horario: { turno: 'L-V', dias: { Lunes: { entrada: '09:00', salida: '18:00' }, Martes: { entrada: '09:00', salida: '18:00' }, Miércoles: { entrada: '09:00', salida: '18:00' }, Jueves: { entrada: '09:00', salida: '18:00' }, Viernes: { entrada: '09:00', salida: '18:00' } } },
        ubicacion: 'corporativo',
        direccion: 'Av. Vallarta #5678, Col. Americana, Guadalajara, Jalisco',
        actividades: 'Prospección y captación de clientes corporativos.\nPresentación de propuestas comerciales.\nNegociación de contratos y precios.\nSeguimiento postventa y fidelización de cuentas clave.',
        conocimientos: 'Técnicas de venta consultiva, manejo de CRM (Salesforce/HubSpot), Office avanzado, análisis de mercado.',
        ofrecemos: 'Sueldo base + comisiones sin tope, auto utilitario, celular corporativo, capacitación en ventas.',
        beneficios: 'Seguro de gastos médicos, vales de gasolina, bono por cumplimiento de cuota, viáticos cubiertos.',
        vacanteId: null
      },
      {
        id: 1003,
        titulo: 'Coordinador de Marketing Digital',
        departamento: 'Marketing',
        justificacion: 'Se necesita un perfil especializado para liderar la estrategia digital y campañas en redes sociales de la empresa.',
        descripcionPuesto: 'Coordinar la estrategia de marketing digital, gestionar campañas de publicidad en línea, analizar métricas de rendimiento y liderar al equipo de contenido.',
        requisitosPuesto: 'Licenciatura en Marketing, Comunicación o afín, 3+ años en marketing digital, experiencia con Google Ads, Meta Ads y herramientas de analítica.',
        tipo: 'nuevo',
        cantidadVacantes: 1,
        puestoReferencia: null,
        solicitante: 'jefe-marketing',
        fechaSolicitud: '2026-02-14',
        estado: 'aprobada-do',
        aprobacionDO: { estado: 'aprobada', comentario: 'Aprobado, es indispensable fortalecer nuestra presencia digital.', fecha: '2026-02-15' },
        aprobacionFinanzas: { estado: 'pendiente', comentario: '', fecha: '' },
        sueldoDesde: '20000',
        sueldoHasta: '30000',
        jornada: 'Tiempo completo',
        duracion: 'Por tiempo indeterminado',
        horario: { turno: 'L-V', dias: { Lunes: { entrada: '09:00', salida: '18:00' }, Martes: { entrada: '09:00', salida: '18:00' }, Miércoles: { entrada: '09:00', salida: '18:00' }, Jueves: { entrada: '09:00', salida: '18:00' }, Viernes: { entrada: '09:00', salida: '18:00' } } },
        ubicacion: 'corporativo',
        direccion: 'Av. Vallarta #5678, Col. Americana, Guadalajara, Jalisco',
        actividades: 'Planificación y ejecución de campañas digitales (Google Ads, Meta Ads).\nGestión de redes sociales y calendario de contenido.\nAnálisis de KPIs y optimización de campañas.\nCoordinación con agencias externas y equipo creativo interno.',
        conocimientos: 'Google Ads, Meta Business Suite, Google Analytics 4, SEO/SEM, herramientas de diseño (Canva/Figma), email marketing.',
        ofrecemos: 'Esquema híbrido (3 días oficina, 2 home office), prestaciones de ley, capacitación en certificaciones de Google.',
        beneficios: 'Seguro de gastos médicos, día de cumpleaños libre, acceso a plataformas de e-learning, bono anual por desempeño.',
        vacanteId: null
      }
    );
    saveData();
  }

  // Demo empleados
  if (empleados.length === 0) {
    empleados = [
      {
        id: 1, nombre: 'Carlos', apellidos: 'L\u00f3pez',
        departamento: 'IT', puesto: 'Desarrollador Full Stack',
        descripcionPuesto: 'Desarrollo y mantenimiento de aplicaciones web full stack utilizando React, Node.js y bases de datos SQL/NoSQL. Participaci\u00f3n en dise\u00f1o de arquitectura y code reviews.',
        requisitosPuesto: 'Ingenier\u00eda en Sistemas o af\u00edn, 3+ a\u00f1os de experiencia en desarrollo web, conocimiento de Git, metodolog\u00edas \u00e1giles, React, Node.js.',
        tipoContrato: 'Tiempo Completo', salario: '$30,000',
        ubicacion: 'Guadalajara, Jalisco', fechaIngreso: '2023-03-15', jefe: 'Director de IT'
      },
      {
        id: 2, nombre: 'Mar\u00eda', apellidos: 'Garc\u00eda',
        departamento: 'IT', puesto: 'Soporte de TI',
        descripcionPuesto: 'Atenci\u00f3n y resoluci\u00f3n de incidentes de TI, administraci\u00f3n de equipos, configuraci\u00f3n de redes y soporte a usuarios internos.',
        requisitosPuesto: 'T\u00e9cnico o Ingenier\u00eda en Sistemas, 2+ a\u00f1os en soporte t\u00e9cnico, conocimiento de Windows/Linux, redes LAN/WAN.',
        tipoContrato: 'Tiempo Completo', salario: '$18,000',
        ubicacion: 'Guadalajara, Jalisco', fechaIngreso: '2022-08-01', jefe: 'Director de IT'
      },
      {
        id: 3, nombre: 'Ana', apellidos: 'Rodr\u00edguez',
        departamento: 'IT', puesto: 'Analista de Datos',
        descripcionPuesto: 'An\u00e1lisis de datos empresariales, creaci\u00f3n de dashboards y reportes, modelado de datos y apoyo en toma de decisiones basada en datos.',
        requisitosPuesto: 'Ingenier\u00eda, Matem\u00e1ticas o af\u00edn, 2+ a\u00f1os en an\u00e1lisis de datos, dominio de SQL, Python/R, herramientas de BI (Power BI/Tableau).',
        tipoContrato: 'Tiempo Completo', salario: '$25,000',
        ubicacion: 'Guadalajara, Jalisco', fechaIngreso: '2024-01-10', jefe: 'Director de IT'
      },
      {
        id: 4, nombre: 'Roberto', apellidos: 'Mart\u00ednez',
        departamento: 'Ventas', puesto: 'Ejecutivo de Ventas',
        descripcionPuesto: 'Prospecci\u00f3n y cierre de ventas B2B, gesti\u00f3n de cartera de clientes, cumplimiento de metas comerciales y elaboraci\u00f3n de cotizaciones.',
        requisitosPuesto: 'Licenciatura en Administraci\u00f3n, Marketing o af\u00edn, 2+ a\u00f1os en ventas, habilidades de negociaci\u00f3n, manejo de CRM.',
        tipoContrato: 'Tiempo Completo', salario: '$20,000',
        ubicacion: 'Zapopan, Jalisco', fechaIngreso: '2023-06-01', jefe: 'Gerente de Ventas'
      },
      {
        id: 5, nombre: 'Laura', apellidos: 'S\u00e1nchez',
        departamento: 'Ventas', puesto: 'Coordinador Comercial',
        descripcionPuesto: 'Coordinaci\u00f3n del equipo de ventas, seguimiento de KPIs comerciales, desarrollo de estrategias de venta y capacitaci\u00f3n del equipo.',
        requisitosPuesto: 'Licenciatura en Negocios o af\u00edn, 3+ a\u00f1os en ventas con experiencia en liderazgo, manejo de equipos, an\u00e1lisis de indicadores.',
        tipoContrato: 'Tiempo Completo', salario: '$28,000',
        ubicacion: 'Zapopan, Jalisco', fechaIngreso: '2022-11-15', jefe: 'Director Comercial'
      },
      {
        id: 6, nombre: 'Pedro', apellidos: 'Hern\u00e1ndez',
        departamento: 'Marketing', puesto: 'Dise\u00f1ador Gr\u00e1fico',
        descripcionPuesto: 'Creaci\u00f3n de material gr\u00e1fico para campa\u00f1as digitales e impresas, dise\u00f1o de identidad visual, edici\u00f3n de fotograf\u00eda y video.',
        requisitosPuesto: 'Licenciatura en Dise\u00f1o Gr\u00e1fico o af\u00edn, 2+ a\u00f1os de experiencia, dominio de Adobe Creative Suite (Photoshop, Illustrator, InDesign).',
        tipoContrato: 'Tiempo Completo', salario: '$22,000',
        ubicacion: 'Guadalajara, Jalisco', fechaIngreso: '2023-09-01', jefe: 'Director de Marketing'
      },
      {
        id: 7, nombre: 'Sof\u00eda', apellidos: 'Ram\u00edrez',
        departamento: 'Marketing', puesto: 'Community Manager',
        descripcionPuesto: 'Gesti\u00f3n de redes sociales, creaci\u00f3n de contenido digital, interacci\u00f3n con la comunidad, an\u00e1lisis de m\u00e9tricas y reportes de engagement.',
        requisitosPuesto: 'Licenciatura en Comunicaci\u00f3n o Marketing, 1+ a\u00f1o en manejo de redes sociales, conocimiento de herramientas de programaci\u00f3n y anal\u00edtica social.',
        tipoContrato: 'Tiempo Completo', salario: '$17,000',
        ubicacion: 'Guadalajara, Jalisco', fechaIngreso: '2024-03-01', jefe: 'Director de Marketing'
      },
      {
        id: 8, nombre: 'Diego', apellidos: 'Torres',
        departamento: 'Operaciones', puesto: 'Supervisor de Almac\u00e9n',
        descripcionPuesto: 'Supervisi\u00f3n de operaciones de almac\u00e9n, control de inventarios, coordinaci\u00f3n de recepci\u00f3n y despacho de mercanc\u00eda, gesti\u00f3n del equipo operativo.',
        requisitosPuesto: 'Ingenier\u00eda Industrial o Log\u00edstica, 3+ a\u00f1os en operaciones de almac\u00e9n, conocimiento de WMS, liderazgo de equipos operativos.',
        tipoContrato: 'Tiempo Completo', salario: '$24,000',
        ubicacion: 'Tlaquepaque, Jalisco', fechaIngreso: '2022-05-20', jefe: 'Director de Operaciones'
      },
      {
        id: 9, nombre: 'Carmen', apellidos: 'Flores',
        departamento: 'Operaciones', puesto: 'Analista de Log\u00edstica',
        descripcionPuesto: 'An\u00e1lisis y optimizaci\u00f3n de rutas de distribuci\u00f3n, gesti\u00f3n de proveedores de transporte, seguimiento de env\u00edos y control de costos log\u00edsticos.',
        requisitosPuesto: 'Ingenier\u00eda Industrial o Log\u00edstica, 2+ a\u00f1os en log\u00edstica, manejo de Excel avanzado, conocimiento de sistemas ERP.',
        tipoContrato: 'Tiempo Completo', salario: '$21,000',
        ubicacion: 'Tlaquepaque, Jalisco', fechaIngreso: '2023-07-10', jefe: 'Director de Operaciones'
      },
      {
        id: 10, nombre: 'Fernando', apellidos: 'D\u00edaz',
        departamento: 'Finanzas', puesto: 'Contador General',
        descripcionPuesto: 'Registro contable, elaboraci\u00f3n de estados financieros, c\u00e1lculo y presentaci\u00f3n de impuestos, conciliaciones bancarias y auditor\u00edas internas.',
        requisitosPuesto: 'Licenciatura en Contadur\u00eda P\u00fablica, 3+ a\u00f1os de experiencia, conocimiento de normatividad fiscal mexicana, manejo de CONTPAQi o SAP.',
        tipoContrato: 'Tiempo Completo', salario: '$26,000',
        ubicacion: 'Guadalajara, Jalisco', fechaIngreso: '2023-01-15', jefe: 'Director de Finanzas'
      }
    ];
    saveData();
  }

  // Detect current page and render accordingly
  const isAdmin = !!document.getElementById('view-gestion-dashboard');
  const isPortal = !!document.getElementById('vacantesGrid') && !isAdmin;
  const isSeguimiento = !!document.getElementById('input-codigo');

  configurarValidacionEspanol();
  if (!isSeguimiento) crearCalendarioDropdown();

  if (isSeguimiento) {
    const params = new URLSearchParams(window.location.search);
    const codigoParam = params.get('codigo');
    if (codigoParam && /^\d{6}$/.test(codigoParam)) {
      const input = document.getElementById('input-codigo');
      if (input) input.value = codigoParam;
      consultarPostulacion();
    }
  } else if (isPortal) {
    poblarFiltrosPortal();
    setupFiltrosPortal();
    renderVacantesPortal();
  } else if (isAdmin) {
    verificarSesion().then(ok => {
      if (ok) aplicarVistasPorRol();
    });
  }
});
