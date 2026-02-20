// ==================== DATA STORAGE ====================
let vacantes = JSON.parse(localStorage.getItem('vacantes') || '[]');
let candidatos = JSON.parse(localStorage.getItem('candidatos') || '[]');
let entrevistas = JSON.parse(localStorage.getItem('entrevistas') || '[]');
let solicitudes = JSON.parse(localStorage.getItem('solicitudes') || '[]');
let empleados = JSON.parse(localStorage.getItem('empleados') || '[]');
let notificaciones = JSON.parse(localStorage.getItem('notificaciones') || '[]');
let rolActual = (window.__VACANTES_CONFIG && window.__VACANTES_CONFIG.rol) || 'rh';
let sesionUsuario = window.__VACANTES_CONFIG || null;

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

  const nameEl = document.getElementById('user-name');
  const roleEl = document.getElementById('user-role');
  if (nameEl) nameEl.textContent = cfg.userName || '';
  if (roleEl) roleEl.textContent = cfg.rolNombre || '';

  // Doble rol: mostrar mini-selector
  if (cfg.dobleRol) {
    const selectDoble = document.getElementById('select-rol-doble');
    if (selectDoble) {
      selectDoble.style.display = '';
      selectDoble.value = rolActual;
    }
  }

  return true;
}

async function cerrarSesion() {
  const cfg = window.__VACANTES_CONFIG;
  window.location.href = (cfg && cfg.logoutUrl) || '/';
}

function aplicarVistasPorRol() {
  const tabsRH = document.querySelectorAll('.tab-rh');
  const tabJefe = document.querySelector('.tab-jefe');
  const tabAprobacion = document.querySelector('.tab-aprobacion');

  tabsRH.forEach(t => t.style.display = 'none');
  if (tabJefe) tabJefe.style.display = 'none';
  if (tabAprobacion) tabAprobacion.style.display = 'none';

  if (esRolJefe()) {
    if (tabJefe) tabJefe.style.display = '';
    showView('solicitudes-jefe');
  } else if (rolActual === 'gerente-finanzas' || rolActual === 'gerente-do') {
    if (tabAprobacion) tabAprobacion.style.display = '';
    showView('solicitudes-aprobacion');
  } else {
    tabsRH.forEach(t => t.style.display = '');
    showView('gestion-dashboard');
  }

  contarNoLeidas();
}

// ==================== NAVIGATION ====================
function showView(viewName) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  const target = document.getElementById(`view-${viewName}`);
  if (!target) return;
  target.classList.add('active');

  if (viewName === 'gestion-vacantes') {
    renderVacantesGestion();
    populateVacanteFilters();
    renderSolicitudesPreaprobadas();
  } else if (viewName === 'gestion-candidatos') {
    renderCandidatosTable();
    populateCandidatoFilters();
  } else if (viewName === 'gestion-dashboard') {
    renderDashboard();
  } else if (viewName === 'solicitudes-jefe') {
    renderSolicitudesJefe();
  } else if (viewName === 'solicitudes-aprobacion') {
    renderSolicitudesAprobacion();
  }
}

// ==================== MODALS ====================
function openModal(modalName) {
  document.getElementById(`modal-${modalName}`).classList.add('show');
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
      <span class="cal-title"><span id="cal-titulo-mes" class="cal-mes-clickable" onclick="mostrarSelectorMes()"></span> <span id="cal-titulo-anio" class="cal-anio-clickable" onclick="mostrarSelectorAnio()"></span></span>
      <button type="button" class="cal-nav" id="cal-nav-next" onclick="calNavNext()">&rsaquo;</button>
    </div>
    <div class="cal-dias-header" id="cal-dias-header-wrap">${DIAS_SEMANA_ES.map(d => `<span>${d}</span>`).join('')}</div>
    <div class="cal-dias" id="cal-dias"></div>
    <div class="cal-meses-grid" id="cal-meses-grid" style="display:none;"></div>
    <div class="cal-anios-grid" id="cal-anios-grid" style="display:none;"></div>
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
  let anio, mes;
  const val = input.value;
  if (val && val.length === 10) {
    const partes = val.split('/');
    if (partes.length === 3) {
      mes = parseInt(partes[1], 10) - 1;
      anio = parseInt(partes[2], 10);
    }
  }
  if (anio === undefined || isNaN(anio)) {
    const hoy = new Date();
    anio = hoy.getFullYear();
    mes = hoy.getMonth();
  }

  window.__calAnioActual = anio;
  window.__calMesActual = mes;

  renderMesCalendario(anio, mes);
  dd.style.display = 'block';
  dd.classList.add('show');
}

function cerrarCalendario() {
  if (window.__calDropdown) {
    window.__calDropdown.classList.remove('show');
    window.__calDropdown.style.display = 'none';
  }
}

function renderMesCalendario(anio, mes) {
  window.__calVistaAnios = false;
  window.__calVistaMeses = false;
  document.getElementById('cal-titulo-mes').textContent = MESES_ES[mes];
  document.getElementById('cal-titulo-anio').textContent = anio;
  // Asegurar que la vista de días esté visible
  document.getElementById('cal-dias-header-wrap').style.display = '';
  document.getElementById('cal-dias').style.display = '';
  document.getElementById('cal-footer-wrap').style.display = '';
  document.getElementById('cal-anios-grid').style.display = 'none';
  document.getElementById('cal-meses-grid').style.display = 'none';
  document.getElementById('cal-nav-prev').style.visibility = '';
  document.getElementById('cal-nav-next').style.visibility = '';

  const primerDia = new Date(anio, mes, 1);
  const ultimoDia = new Date(anio, mes + 1, 0);
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
  const mesAnteriorUltimo = new Date(anio, mes, 0).getDate();
  for (let i = diaInicio - 1; i >= 0; i--) {
    const d = mesAnteriorUltimo - i;
    const mAnt = mes === 0 ? 11 : mes - 1;
    const aAnt = mes === 0 ? anio - 1 : anio;
    html += `<button type="button" class="cal-dia otro-mes" onclick="seleccionarDiaCalendario(${d},${mAnt},${aAnt})">${d}</button>`;
  }

  // Días del mes actual
  for (let d = 1; d <= diasEnMes; d++) {
    let clases = 'cal-dia';
    if (d === hoyDia && mes === hoyMes && anio === hoyAnio) clases += ' hoy';
    if (d === selDia && mes === selMes && anio === selAnio) clases += ' seleccionado';
    html += `<button type="button" class="${clases}" onclick="seleccionarDiaCalendario(${d},${mes},${anio})">${d}</button>`;
  }

  // Días del mes siguiente para completar la grilla
  const totalCeldas = diaInicio + diasEnMes;
  const restantes = totalCeldas % 7 === 0 ? 0 : 7 - (totalCeldas % 7);
  for (let d = 1; d <= restantes; d++) {
    const mSig = mes === 11 ? 0 : mes + 1;
    const aSig = mes === 11 ? anio + 1 : anio;
    html += `<button type="button" class="cal-dia otro-mes" onclick="seleccionarDiaCalendario(${d},${mSig},${aSig})">${d}</button>`;
  }

  diasContainer.innerHTML = html;
}

function seleccionarDiaCalendario(dia, mes, anio) {
  const dd = String(dia).padStart(2, '0');
  const mm = String(mes + 1).padStart(2, '0');
  const valor = `${dd}/${mm}/${anio}`;

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
  document.getElementById('cal-anios-grid').style.display = 'none';
  // Flechas para cambiar año mientras se eligen meses
  document.getElementById('cal-nav-prev').style.visibility = '';
  renderSelectorMes();
}

function renderSelectorMes() {
  const grid = document.getElementById('cal-meses-grid');
  const mesSel = window.__calMesActual;
  const anio = window.__calAnioActual;
  const hoy = new Date();
  const hoyMes = hoy.getMonth();
  const hoyAnio = hoy.getFullYear();

  document.getElementById('cal-titulo-mes').textContent = '';
  document.getElementById('cal-titulo-anio').textContent = anio;

  // Ocultar → si el año ya es el actual (no avanzar más)
  document.getElementById('cal-nav-next').style.visibility = anio >= hoyAnio ? 'hidden' : '';

  const MESES_CORTOS = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
  let html = '';
  for (let m = 0; m < 12; m++) {
    let clases = 'cal-mes-item';
    if (m === mesSel && !window.__calVistaAnios) clases += ' seleccionado';
    if (m === hoyMes && anio === hoyAnio) clases += ' hoy';
    const esFuturo = anio === hoyAnio && m > hoyMes;
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
  const grid = document.getElementById('cal-anios-grid');
  const anioSel = window.__calAnioActual;
  const hoyAnio = new Date().getFullYear();
  const inicio = window.__calAniosPagInicio;
  const fin = inicio + 11;

  // Actualizar título con rango
  document.getElementById('cal-titulo-mes').textContent = inicio + ' - ' + Math.min(fin, hoyAnio);
  document.getElementById('cal-titulo-anio').textContent = '';

  // Ocultar flecha → si la página ya contiene el año actual
  document.getElementById('cal-nav-next').style.visibility = fin >= hoyAnio ? 'hidden' : '';

  let html = '';
  for (let a = inicio; a <= fin; a++) {
    let clases = 'cal-anio-item';
    if (a === anioSel) clases += ' seleccionado';
    if (a === hoyAnio) clases += ' hoy';
    if (a > hoyAnio) clases += ' disabled';
    const disabled = a > hoyAnio ? ' disabled' : '';
    html += `<button type="button" class="${clases}"${disabled} onclick="seleccionarAnio(${a})">${a}</button>`;
  }
  grid.innerHTML = html;
  grid.style.display = 'grid';
}

function seleccionarAnio(anio) {
  window.__calVistaAnios = false;
  window.__calAnioActual = anio;
  document.getElementById('cal-nav-next').style.visibility = '';
  renderMesCalendario(anio, window.__calMesActual);
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
    let anio = window.__calAnioActual;
    if (mes < 0) { mes = 11; anio--; }
    window.__calMesActual = mes;
    window.__calAnioActual = anio;
    renderMesCalendario(anio, mes);
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
    let anio = window.__calAnioActual;
    if (mes > 11) { mes = 0; anio++; }
    window.__calMesActual = mes;
    window.__calAnioActual = anio;
    renderMesCalendario(anio, mes);
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
function renderVacantesPortal() {
  const grid = document.getElementById('vacantesGrid');
  const vacantesAbiertas = vacantes.filter(v => v.estado === 'abierta');

  if (vacantesAbiertas.length === 0) {
    grid.innerHTML = `
      <div class="empty-state">
        <svg viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <h3>No hay vacantes disponibles</h3>
        <p>Por el momento no hay posiciones abiertas</p>
      </div>
    `;
    return;
  }

  grid.innerHTML = vacantesAbiertas.map(v => `
    <div class="vacante-card" onclick="verDetalleVacantePublica(${v.id})" style="cursor:pointer">
      <h3 class="vacante-title">${escapeHtml(v.titulo)}</h3>
      <div class="vacante-info">
        <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(v.departamento)}</div>
        <div class="vacante-info-item"><strong>Ubicaci\u00f3n:</strong> ${escapeHtml(v.ubicacionClave ? (UBICACION_NOMBRES[v.ubicacionClave] || v.ubicacion) : v.ubicacion)}</div>
        <div class="vacante-info-item"><strong>Jornada:</strong> ${escapeHtml(v.jornada || v.tipo)}</div>
        ${v.duracion ? `<div class="vacante-info-item"><strong>Duraci\u00f3n:</strong> ${escapeHtml(v.duracion)}</div>` : ''}
        ${v.salario ? `<div class="vacante-info-item"><strong>Salario:</strong> ${escapeHtml(v.salario)}</div>` : ''}
      </div>
      <p class="vacante-desc">${escapeHtml(v.descripcion).substring(0, 150)}...</p>
      <button class="btn btn-primary btn-small" onclick="event.stopPropagation();aplicarVacante(${v.id})" style="margin-top:auto;align-self:flex-end;">Aplicar Ahora</button>
    </div>
  `).join('');
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
          <p><strong>T\u00edtulo:</strong> ${escapeHtml(vacante.titulo)}</p>
          <p><strong>Departamento:</strong> ${escapeHtml(vacante.departamento)}</p>
          <p><strong>Ubicaci\u00f3n:</strong> ${escapeHtml(vacante.ubicacionClave ? (UBICACION_NOMBRES[vacante.ubicacionClave] || vacante.ubicacion) : vacante.ubicacion)}</p>
          ${vacante.direccion ? `<p><strong>Direcci\u00f3n:</strong> ${escapeHtml(vacante.direccion)}</p>` : ''}
        </div>
        <div class="col-6">
          ${vacante.jornada ? `<p><strong>Jornada:</strong> ${escapeHtml(vacante.jornada)}</p>` : `<p><strong>Tipo:</strong> ${escapeHtml(vacante.tipo)}</p>`}
          ${vacante.duracion ? `<p><strong>Duraci\u00f3n:</strong> ${escapeHtml(vacante.duracion)}</p>` : ''}
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
  const searchTerm = document.getElementById('vac-search')?.value.toLowerCase() || '';
  const filterDepartamento = document.getElementById('vac-filter-departamento')?.value || '';
  const filterEstado = document.getElementById('vac-filter-estado')?.value || '';

  let vacantesFiltradas = vacantes.filter(v => {
    const matchSearch = v.titulo.toLowerCase().includes(searchTerm);
    const matchDepartamento = !filterDepartamento || v.departamento === filterDepartamento;
    const matchEstado = !filterEstado || v.estado === filterEstado;

    return matchSearch && matchDepartamento && matchEstado;
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

  grid.innerHTML = vacantesFiltradas.map(v => {
    const candidatosCount = candidatos.filter(c => c.vacanteId === v.id).length;
    const diasPublicada = Math.floor((new Date() - new Date(v.fechaCreacion)) / (1000 * 60 * 60 * 24));

    return `
      <div class="vacante-card" onclick="verDetalleVacante(${v.id})" style="cursor:pointer">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
          <h3 class="vacante-title">${escapeHtml(v.titulo)}</h3>
          <span class="vacante-status status-${v.estado}">${v.estado === 'abierta' ? 'ABIERTA' : 'CERRADA'}</span>
        </div>
        <div class="vacante-info">
          <div class="vacante-info-item"><strong>Departamento:</strong> ${escapeHtml(v.departamento)}</div>
          <div class="vacante-info-item"><strong>Candidatos:</strong> ${candidatosCount}</div>
          <div class="vacante-info-item"><strong>D\u00edas publicada:</strong> ${diasPublicada} d\u00edas</div>
          <div class="vacante-info-item"><strong>Publicada:</strong> ${formatFecha(v.fechaCreacion)}</div>
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
  // Already populated in HTML
}

function aplicarVacante(vacanteId) {
  document.getElementById('aplicar-vacante-id').value = vacanteId;
  const vacante = vacantes.find(v => v.id === vacanteId);
  const nombreEl = document.getElementById('aplicar-vacante-nombre');
  if (nombreEl && vacante) nombreEl.textContent = vacante.titulo;
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
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Aplicados</div>
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

function filtrarCandidatos() {
  const searchTerm = document.getElementById('cand-search')?.value.toLowerCase() || '';
  const filterVacante = document.getElementById('cand-filter-vacante')?.value || '';
  const filterEtapa = document.getElementById('cand-filter-etapa')?.value || '';

  let candidatosFiltrados = candidatos.filter(c => {
    const nombreCompleto = `${c.nombre} ${c.apellidos}`.toLowerCase();
    const matchSearch = nombreCompleto.includes(searchTerm);
    const matchVacante = !filterVacante || c.vacanteId === parseInt(filterVacante);
    const matchEtapa = !filterEtapa || c.etapa === filterEtapa;

    return matchSearch && matchVacante && matchEtapa;
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
    'aplicado': 'Aplicado', 'entrevista-rh': 'Entrevista RH',
    'primer-filtro': 'Primer Filtro', 'entrevista-jefe': 'Entrevista Jefe',
    'revision-medica': 'Revisi\u00f3n M\u00e9dica', 'psicometrico': 'Psicom\u00e9trico',
    'referencias': 'Referencias', 'documentos': 'Documentos',
    'contratado': 'Contratado', 'rechazado': 'Rechazado'
  };
  return labels[etapa] || 'Aplicado';
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

    ${candidato.curriculum ? `
    <div class="card">
      <div class="card-header">
        <h2>Curriculum Vitae</h2>
      </div>
      <p><strong>Archivo:</strong> ${escapeHtml(candidato.curriculum.nombre)}</p>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;">
        <a class="btn btn-primary btn-small" href="${candidato.curriculum.data}" download="${escapeHtml(candidato.curriculum.nombre)}" style="text-decoration:none;">Descargar CV</a>
        ${candidato.curriculum.tipo === 'application/pdf' ? `<button class="btn btn-ghost btn-small" onclick="verCVEnLinea(${candidato.id})">Ver PDF</button>` : ''}
      </div>
    </div>
    ` : ''}

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
        ${etapaActual === 1 ? `<button class="btn btn-primary" onclick="agendarEntrevistaRH(${candidato.id})">Agendar Entrevista RH</button>` : ''}
        ${etapaActual === 2 ? `<button class="btn btn-primary" onclick="avanzarEtapa(${candidato.id}, 'primer-filtro')">Aprobar Primer Filtro</button>` : ''}
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

    ${candidato.curriculum ? `
    <div class="card">
      <div class="card-header"><h2>Curriculum Vitae</h2></div>
      <p><strong>Archivo:</strong> ${escapeHtml(candidato.curriculum.nombre)}</p>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;">
        <a class="btn btn-primary btn-small" href="${candidato.curriculum.data}" download="${escapeHtml(candidato.curriculum.nombre)}" style="text-decoration:none;">Descargar CV</a>
        ${candidato.curriculum.tipo === 'application/pdf' ? `<button class="btn btn-ghost btn-small" onclick="verCVEnLinea(${candidato.id})">Ver PDF</button>` : ''}
      </div>
    </div>
    ` : ''}

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
    { num: 1, label: 'Aplicar en l\u00ednea' },
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

  return steps.map((step, index) => {
    const isRejected = esRechazado && step.num === etapaRechazo;
    const isCompleted = esRechazado ? step.num < etapaRechazo : step.num < etapaActual;
    const isActive = !esRechazado && step.num === etapaActual;
    const showConnector = index < steps.length - 1;

    return `
      <div class="step ${isActive ? 'active' : ''} ${isCompleted ? 'completed' : ''} ${isRejected ? 'rejected' : ''}">
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
  closeModal('detalle-candidato');
  openModal('agendar-entrevista');
}

function agendarEntrevistaJefe(candidatoId) {
  document.getElementById('entrevista-candidato-id').value = candidatoId;
  document.getElementById('entrevista-tipo').value = 'jefe';
  closeModal('detalle-candidato');
  openModal('agendar-entrevista');
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
  filtrarDashboard();
}

function filtrarDashboard() {
  const fechaInicioRaw = document.getElementById('dash-fecha-inicio')?.value || '';
  const fechaFinRaw = document.getElementById('dash-fecha-fin')?.value || '';
  const fechaInicio = fechaDDMMAAAAaISO(fechaInicioRaw) || fechaInicioRaw;
  const fechaFin = fechaDDMMAAAAaISO(fechaFinRaw) || fechaFinRaw;

  let candidatosFiltrados = candidatos;
  let vacantesFiltradas = vacantes;

  if (fechaInicio || fechaFin) {
    const inicio = fechaInicio ? new Date(fechaInicio) : new Date('2000-01-01');
    const fin = fechaFin ? new Date(fechaFin) : new Date('2099-12-31');

    candidatosFiltrados = candidatos.filter(c => {
      const fecha = new Date(c.fechaAplicacion);
      return fecha >= inicio && fecha <= fin;
    });

    vacantesFiltradas = vacantes.filter(v => {
      const fecha = new Date(v.fechaCreacion);
      return fecha >= inicio && fecha <= fin;
    });
  }

  const totalVacantesActivas = vacantesFiltradas.filter(v => v.estado === 'abierta').length;
  const totalCandidatos = candidatosFiltrados.length;
  const enProceso = candidatosFiltrados.filter(c =>
    !['contratado', 'rechazado'].includes(c.etapa)
  ).length;
  const contratados = candidatosFiltrados.filter(c => c.etapa === 'contratado').length;

  document.getElementById('dash-total-vacantes').textContent = totalVacantesActivas;
  document.getElementById('dash-total-candidatos').textContent = totalCandidatos;
  document.getElementById('dash-en-proceso').textContent = enProceso;
  document.getElementById('dash-contratados').textContent = contratados;

  renderDashboardPorVacante(candidatosFiltrados, vacantesFiltradas);
}

function renderDashboardPorVacante(candidatosFiltrados, vacantesFiltradas) {
  const container = document.getElementById('dashboard-por-vacante');

  if (vacantesFiltradas.length === 0) {
    container.innerHTML = '<p style="padding:20px;text-align:center;color:var(--muted);">No hay datos para mostrar en el rango de fechas seleccionado</p>';
    return;
  }

  const estadisticas = vacantesFiltradas.map(v => {
    const candidatosVacante = candidatosFiltrados.filter(c => c.vacanteId === v.id);
    const diasPublicada = Math.floor((new Date() - new Date(v.fechaCreacion)) / (1000 * 60 * 60 * 24));

    const enProceso = candidatosVacante.filter(c =>
      !['contratado', 'rechazado'].includes(c.etapa)
    ).length;

    const contratados = candidatosVacante.filter(c => c.etapa === 'contratado').length;
    const rechazados = candidatosVacante.filter(c => c.etapa === 'rechazado').length;

    return { vacante: v, totalCandidatos: candidatosVacante.length, diasPublicada, enProceso, contratados, rechazados };
  });

  container.innerHTML = `
    <div style="display:grid;gap:16px;">
      ${estadisticas.map(stat => `
        <div class="vacante-card" onclick="verDetalleVacante(${stat.vacante.id})" style="cursor:pointer">
          <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px;">
            <div>
              <h3 style="margin:0 0 4px 0;font-size:18px;font-weight:800;">${escapeHtml(stat.vacante.titulo)}</h3>
              <p style="margin:0;font-size:13px;color:var(--muted);">${escapeHtml(stat.vacante.departamento)} \u2022 ${escapeHtml(stat.vacante.ubicacion)}</p>
            </div>
            <span class="vacante-status status-${stat.vacante.estado}">${stat.vacante.estado === 'abierta' ? 'ABIERTA' : 'CERRADA'}</span>
          </div>

          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;margin-top:16px;">
            <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
              <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">D\u00edas Publicada</div>
              <div style="font-size:24px;font-weight:900;color:var(--primary);">${stat.diasPublicada}</div>
            </div>
            <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
              <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Total Candidatos</div>
              <div style="font-size:24px;font-weight:900;color:#3b82f6;">${stat.totalCandidatos}</div>
            </div>
            <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
              <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">En Proceso</div>
              <div style="font-size:24px;font-weight:900;color:#f59e0b;">${stat.enProceso}</div>
            </div>
            <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
              <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Contratados</div>
              <div style="font-size:24px;font-weight:900;color:#10b981;">${stat.contratados}</div>
            </div>
            <div style="text-align:center;padding:12px;background:#f9fafb;border-radius:8px;">
              <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Rechazados</div>
              <div style="font-size:24px;font-weight:900;color:#dc2626;">${stat.rechazados}</div>
            </div>
          </div>

          <div style="display:flex;gap:8px;margin-top:auto;">
            <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verDetalleVacante(${stat.vacante.id})">Ver Detalle</button>
            <button class="btn btn-ghost btn-small" onclick="event.stopPropagation();verCandidatosVacante(${stat.vacante.id})">Ver Candidatos</button>
          </div>
        </div>
      `).join('')}
    </div>
  `;
}

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

  grid.innerHTML = misSolicitudes.map(sol => {
    const estadoCalc = calcularEstadoSolicitud(sol);
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
        <button class="btn btn-ghost btn-small" style="margin-top:auto;" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button>
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

  if (pendientes.length === 0) {
    gridPendientes.innerHTML = '<p style="padding:20px;text-align:center;color:var(--muted);">No hay solicitudes pendientes de revisi\u00f3n</p>';
  } else {
    gridPendientes.innerHTML = pendientes.map(sol => `
      <div class="vacante-card" onclick="verDetalleSolicitud(${sol.id})" style="cursor:pointer">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
          <h3 class="vacante-title">${escapeHtml(sol.titulo)}</h3>
          <span class="vacante-status status-solicitud-pendiente">${rolActual === 'gerente-finanzas' ? 'APROBADO POR D.O.' : 'PENDIENTE'}</span>
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
          <button class="btn btn-primary btn-small" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button>
        </div>
      </div>
    `).join('');
  }

  if (revisadas.length === 0) {
    gridRevisadas.innerHTML = '<p style="padding:20px;text-align:center;color:var(--muted);">No hay solicitudes revisadas a\u00fan</p>';
  } else {
    gridRevisadas.innerHTML = revisadas.map(sol => {
      const estadoCalc = calcularEstadoSolicitud(sol);
      const campoAprobacion = rolActual === 'gerente-finanzas' ? 'aprobacionFinanzas' : 'aprobacionDO';
      const miDecision = sol[campoAprobacion]?.estado || 'pendiente';
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
          <button class="btn btn-ghost btn-small" style="margin-top:auto;" onclick="event.stopPropagation();verDetalleSolicitud(${sol.id})">Ver Detalle</button>
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
  grid.innerHTML = preaprobadas.map(sol => `
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
      <button class="btn btn-primary btn-small" style="margin-top:auto;" onclick="event.stopPropagation();iniciarCompletarVacante(${sol.id})">Ver Aprobaciones y Publicar</button>
    </div>
  `).join('');
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
          <div style="font-size:11px;color:var(--muted);text-transform:uppercase;margin-bottom:4px;">Aplicados</div>
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
      crearNotificacion('rh', `Vacante completamente aprobada: ${sol.titulo}`, { vista: 'gestion-vacantes', solicitudId: sol.id });
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
    <div class="horario-aplicar">
      <p style="margin:0 0 8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);">Aplicar a todos los d\u00edas</p>
      <div class="horario-fila">
        <span></span>
        <input type="time" id="horario-${prefix}-todos-entrada" value="09:00">
        <input type="time" id="horario-${prefix}-todos-salida" value="18:00">
        <button type="button" class="btn btn-primary btn-small" onclick="aplicarHorarioTodos('${prefix}')">Aplicar</button>
      </div>
    </div>
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
      direccion: sol.direccion || ''
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
      setTimeout(() => verDetalleSolicitud(notif.link.solicitudId), 300);
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

      const vacante = {
        id: Date.now(),
        titulo: document.getElementById('vac-titulo').value,
        departamento: document.getElementById('vac-departamento').value,
        ubicacion: document.getElementById('vac-ubicacion').value,
        tipo: document.getElementById('vac-tipo').value,
        salario: document.getElementById('vac-salario').value,
        descripcion: document.getElementById('vac-descripcion').value,
        requisitos: document.getElementById('vac-requisitos').value,
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
      if (celSub) celSub.textContent = `Has aplicado con \u00e9xito a la vacante ${nombreVacante}, nos pondremos en contacto contigo muy pronto.`;
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

      const entrevista = {
        id: Date.now(),
        candidatoId: candidatoId,
        tipo: tipo,
        fecha: fechaDDMMAAAAaISO(document.getElementById('entrevista-fecha').value) || document.getElementById('entrevista-fecha').value,
        hora: document.getElementById('entrevista-hora').value,
        duracion: document.getElementById('entrevista-duracion').value,
        entrevistador: document.getElementById('entrevista-entrevistador').value,
        lugar: document.getElementById('entrevista-lugar').value,
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
      e.target.reset();
      renderCandidatosTable();
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
          <p style="margin:4px 0 0;font-size:14px;color:var(--muted);">${vacante ? escapeHtml(vacante.titulo) : 'Vacante'} &middot; Aplicado el ${formatFecha(candidato.fechaAplicacion)}</p>
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

  // Demo vacantes
  if (vacantes.length === 0) {
    vacantes = [
      {
        id: 1,
        titulo: 'Desarrollador Full Stack',
        departamento: 'IT',
        ubicacion: 'Corporativo Vallarta',
        ubicacionClave: 'corporativo',
        direccion: 'Av. Ignacio L Vallarta 2025, Col Americana, Lafayette, 44130 Guadalajara, Jal.',
        tipo: 'Tiempo Completo',
        salario: '$25,000 - $35,000',
        descripcion: 'Buscamos un desarrollador con experiencia en React, Node.js y bases de datos SQL/NoSQL.',
        requisitos: 'M\u00ednimo 3 a\u00f1os de experiencia, conocimientos en Git, metodolog\u00edas \u00e1giles',
        estado: 'abierta',
        fechaCreacion: '2026-02-01'
      },
      {
        id: 2,
        titulo: 'Gerente de Ventas',
        departamento: 'Ventas',
        ubicacion: 'Planta 2 Artes',
        ubicacionClave: 'planta2',
        direccion: 'C. Artes 2767, San Rafael, 44810 Guadalajara, Jal.',
        tipo: 'Tiempo Completo',
        salario: '$30,000 - $45,000',
        descripcion: 'Gerente de ventas con experiencia en manejo de equipos y cumplimiento de objetivos.',
        requisitos: '5 a\u00f1os de experiencia, liderazgo de equipos, orientaci\u00f3n a resultados',
        estado: 'abierta',
        fechaCreacion: '2026-02-05'
      }
    ];
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
    renderVacantesPortal();
  } else if (isAdmin) {
    verificarSesion().then(ok => {
      if (ok) aplicarVistasPorRol();
    });
  }
});
