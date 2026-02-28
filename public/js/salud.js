// ==================== DATA STORAGE ====================
var candidatos = JSON.parse(localStorage.getItem('candidatos') || '[]');
var examenesMedicos = JSON.parse(localStorage.getItem('examenesMedicos') || '[]');
var vacantes = JSON.parse(localStorage.getItem('vacantes') || '[]');

var sesionSalud = window.__SALUD_CONFIG || {};
var rolActual = (sesionSalud && sesionSalud.rol) || 'salud';
var candidatoActualId = null;
var autoSaveInterval = null;

// ==================== PERSONAL DE SALUD OCUPACIONAL ====================
var PERSONAL_SALUD = [
  { nombre: 'Gabriela Elizabeth Velazquez Cardenas', email: 'g.velazquez@gibanibb.com' },
  { nombre: 'Nayeli Guadalupe Sanchez Landin', email: 'n.sanchez@gibanibb.com' },
  { nombre: 'Dayana Areli Arellano Martinez', email: 'd.arellano@gibanibb.com' }
];

// ==================== SAVE DATA ====================
function saveData() {
  localStorage.setItem('examenesMedicos', JSON.stringify(examenesMedicos));
}

function saveCandidatos() {
  localStorage.setItem('candidatos', JSON.stringify(candidatos));
}

// ==================== HELPERS ====================
function escapeHtml(str) {
  return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}

function formatFecha(fecha) {
  if (!fecha) return '';
  var d = new Date(fecha + 'T00:00:00');
  if (isNaN(d)) return fecha;
  var meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
  return d.getDate() + ' ' + meses[d.getMonth()] + ' ' + d.getFullYear();
}

function showToast(title, detail) {
  var toast = document.getElementById('toast-salud');
  if (!toast) return;
  toast.innerHTML = '<strong>' + escapeHtml(title) + '</strong>' + (detail ? '<small>' + escapeHtml(detail) + '</small>' : '');
  toast.classList.add('show');
  clearTimeout(window.__toastTimer);
  window.__toastTimer = setTimeout(function() { toast.classList.remove('show'); }, 3200);
}

// ==================== VIEWS ====================
function showView(viewName) {
  document.querySelectorAll('.salud-module .view').forEach(function(v) { v.classList.remove('active'); });
  var target = document.getElementById('view-' + viewName);
  if (!target) return;
  target.classList.add('active');
  if (viewName === 'expedientes') {
    recargarDatos();
    renderPendientes();
    renderHistorial();
  }
}

function volverExpedientes() {
  if (autoSaveInterval) { recolectarDatosFormulario(); saveData(); }
  detenerAutoGuardado();
  candidatoActualId = null;
  showView('expedientes');
}

function recargarDatos() {
  candidatos = JSON.parse(localStorage.getItem('candidatos') || '[]');
  vacantes = JSON.parse(localStorage.getItem('vacantes') || '[]');
  examenesMedicos = JSON.parse(localStorage.getItem('examenesMedicos') || '[]');
}

// ==================== LISTA PENDIENTES ====================
function renderPendientes() {
  var pendientes = candidatos.filter(function(c) { return c.etapa === 'revision-medica'; });
  var container = document.getElementById('tabla-pendientes');
  if (!container) return;

  if (pendientes.length === 0) {
    container.innerHTML = '<div style="text-align:center;padding:24px 0;color:var(--muted);"><p style="font-size:14px;margin:0;">No hay candidatos pendientes de revision medica.</p></div>';
    return;
  }

  var filas = pendientes.map(function(c) {
    var v = vacantes.find(function(vac) { return vac.id === c.vacanteId; });
    var tieneExamen = examenesMedicos.some(function(e) { return e.candidatoId === c.id && !e.resultado; });
    return '<tr>' +
      '<td><strong>' + escapeHtml(c.nombre) + ' ' + escapeHtml(c.apellidos) + '</strong><br><span style="font-size:12px;color:var(--muted);">' + escapeHtml(c.email) + '</span></td>' +
      '<td>' + (v ? escapeHtml(v.titulo) : 'N/A') + '</td>' +
      '<td>' + formatFecha(c.fechaAplicacion) + '</td>' +
      '<td><button class="btn btn-primary btn-small" onclick="abrirFormulario(' + c.id + ')">' +
        '<i class="fas fa-stethoscope"></i> ' + (tieneExamen ? 'Continuar Examen' : 'Iniciar Examen') +
      '</button></td>' +
    '</tr>';
  }).join('');

  container.innerHTML = '<table style="width:100%;"><thead><tr><th>Candidato</th><th>Vacante</th><th>Fecha Postulacion</th><th>Acciones</th></tr></thead><tbody>' + filas + '</tbody></table>';
}

// ==================== LISTA HISTORIAL ====================
function renderHistorial() {
  var busqueda = (document.getElementById('buscar-historial') ? document.getElementById('buscar-historial').value : '').toLowerCase();
  var filtroRes = document.getElementById('filtro-resultado') ? document.getElementById('filtro-resultado').value : '';

  var lista = examenesMedicos
    .filter(function(e) { return e.resultado; })
    .map(function(e) {
      var c = candidatos.find(function(cand) { return cand.id === e.candidatoId; });
      return { examen: e, candidato: c };
    })
    .filter(function(item) {
      if (!item.candidato) return false;
      var nombre = (item.candidato.nombre + ' ' + item.candidato.apellidos).toLowerCase();
      var matchBusqueda = !busqueda || nombre.indexOf(busqueda) !== -1;
      var matchResultado = !filtroRes || item.examen.resultado === filtroRes;
      return matchBusqueda && matchResultado;
    })
    .sort(function(a, b) { return (b.examen.fechaResultado || '').localeCompare(a.examen.fechaResultado || ''); });

  var container = document.getElementById('tabla-historial');
  if (!container) return;

  if (lista.length === 0) {
    container.innerHTML = '<div style="text-align:center;padding:24px 0;color:var(--muted);"><p style="font-size:14px;margin:0;">No hay examenes en el historial.</p></div>';
    return;
  }

  var filas = lista.map(function(item) {
    var v = vacantes.find(function(vac) { return vac.id === item.candidato.vacanteId; });
    var badgeClass = item.examen.resultado === 'apto' ? 'badge-apto' : 'badge-no-apto';
    return '<tr>' +
      '<td><strong>' + escapeHtml(item.candidato.nombre) + ' ' + escapeHtml(item.candidato.apellidos) + '</strong></td>' +
      '<td>' + (v ? escapeHtml(v.titulo) : 'N/A') + '</td>' +
      '<td>' + formatFecha(item.examen.fechaResultado) + '</td>' +
      '<td><span class="badge ' + badgeClass + '">' + (item.examen.resultado === 'apto' ? 'APTO' : 'NO APTO') + '</span></td>' +
      '<td>' + escapeHtml(item.examen.realizadoPor) + '</td>' +
      '<td><button class="btn btn-ghost btn-small" onclick="verDetalleExamen(' + item.examen.id + ')"><i class="fas fa-eye"></i> Ver</button></td>' +
    '</tr>';
  }).join('');

  container.innerHTML = '<table style="width:100%;"><thead><tr><th>Candidato</th><th>Vacante</th><th>Fecha</th><th>Resultado</th><th>Realizado por</th><th>Acciones</th></tr></thead><tbody>' + filas + '</tbody></table>';
}

// ==================== FORMULARIO - ABRIR ====================
function abrirFormulario(candidatoId) {
  recargarDatos();
  candidatoActualId = candidatoId;
  var candidato = candidatos.find(function(c) { return c.id === candidatoId; });
  if (!candidato) return;

  var examen = examenesMedicos.find(function(e) { return e.candidatoId === candidatoId && !e.resultado; });
  if (!examen) {
    examen = {
      id: Date.now(),
      candidatoId: candidatoId,
      fechaAplicacion: new Date().toISOString().split('T')[0],
      datos: {},
      resultado: null,
      comentarioFinal: '',
      realizadoPor: sesionSalud.userName || 'Salud Ocupacional'
    };
    examenesMedicos.push(examen);
    saveData();
  }

  document.getElementById('form-titulo-candidato').textContent = 'SOC-REG-05 - ' + candidato.nombre + ' ' + candidato.apellidos;
  document.getElementById('examen-candidato-id').value = candidatoId;

  renderFormularioDocumento(examen, candidato);
  iniciarAutoGuardado();
  showView('formulario');
}

// ==================== CALCULO IMC ====================
function calcularIMC() {
  var peso = parseFloat(document.getElementById('sm-peso') ? document.getElementById('sm-peso').value : 0);
  var tallaCm = parseFloat(document.getElementById('sm-talla') ? document.getElementById('sm-talla').value : 0);
  var imcEl = document.getElementById('sm-imc');
  if (peso > 0 && tallaCm > 0 && imcEl) {
    var tallaM = tallaCm / 100;
    var imc = (peso / (tallaM * tallaM)).toFixed(1);
    imcEl.value = imc;
    var color = '#16a34a';
    if (imc < 18.5) color = '#f59e0b';
    else if (imc >= 25 && imc < 30) color = '#f59e0b';
    else if (imc >= 30) color = '#dc2626';
    imcEl.style.color = color;
  }
}


// ==================== AUTO-GUARDADO ====================
function iniciarAutoGuardado() {
  detenerAutoGuardado();
  autoSaveInterval = setInterval(function() {
    recolectarDatosFormulario();
    saveData();
  }, 30000);
}

function detenerAutoGuardado() {
  if (autoSaveInterval) { clearInterval(autoSaveInterval); autoSaveInterval = null; }
}

function guardarBorrador() {
  recolectarDatosFormulario();
  saveData();
  showToast('Borrador guardado', 'Los datos del examen han sido guardados');
}

// ==================== RECOLECTAR DATOS DEL FORMULARIO ====================
function recolectarDatosFormulario() {
  var examen = examenesMedicos.find(function(e) { return e.candidatoId === candidatoActualId && !e.resultado; });
  if (!examen) return null;

  examen.datos.datosPersonales = recolectarCamposSeccion('datosPersonales');
  examen.datos.antecedentesLaborales = recolectarAntecedentesLaborales();
  examen.datos.antecedentesNoPatologicos = recolectarAntecedentesNoPatologicos();
  examen.datos.heredoFamiliares = recolectarHeredoFamiliares();
  examen.datos.antecedentesPatologicos = recolectarAntecedentesPatologicos();
  examen.datos.signosVitales = recolectarCamposSeccion('signosVitales');
  examen.datos.somatometria = recolectarCamposSeccion('somatometria');
  examen.datos.exploracionFisica = recolectarExploracionFisica();
  examen.datos.examenesComplementarios = recolectarExamenesComplementarios();
  examen.datos.resultados = recolectarResultados();
  examen.comentarioFinal = (document.getElementById('comentario-concluyente') || {}).value || '';

  return examen;
}

function recolectarCamposSeccion(nombre) {
  var campos = {};
  document.querySelectorAll('[data-seccion="' + nombre + '"]').forEach(function(el) {
    var campo = el.getAttribute('data-campo');
    if (campo) campos[campo] = el.value;
  });
  return campos;
}

function recolectarAntecedentesLaborales() {
  var datos = { agentes: {} };
  document.querySelectorAll('[data-agente]').forEach(function(el) {
    var agente = el.getAttribute('data-agente');
    var campo = el.getAttribute('data-campo');
    if (!datos.agentes[agente]) datos.agentes[agente] = {};
    datos.agentes[agente][campo] = el.value;
  });
  var riesgo = recolectarCamposSeccion('riesgoTrabajo');
  var enfLab = recolectarCamposSeccion('enfermedadLaboral');
  datos.riesgoTrabajo = riesgo;
  datos.enfermedadLaboral = enfLab;
  return datos;
}

function recolectarAntecedentesNoPatologicos() {
  var datos = recolectarCamposSeccion('antecedentesNoPatologicos');
  datos.vacunas = {
    tetanos: (document.querySelector('[data-seccion="vacTetanos"]') || {}).value || '',
    influenza: (document.querySelector('[data-seccion="vacInfluenza"]') || {}).value || '',
    hepatitisB: (document.querySelector('[data-seccion="vacHepatitisB"]') || {}).value || '',
    covid: (document.querySelector('[data-seccion="vacCovid"]') || {}).value || ''
  };
  return datos;
}

function recolectarHeredoFamiliares() {
  var tabla = {};
  document.querySelectorAll('[data-hf]').forEach(function(el) {
    var enf = el.getAttribute('data-hf');
    var fam = el.getAttribute('data-fam');
    if (!tabla[enf]) tabla[enf] = {};
    tabla[enf][fam] = el.value;
  });
  return { tabla: tabla };
}

function recolectarAntecedentesPatologicos() {
  var items = {};
  document.querySelectorAll('[data-patitem]').forEach(function(el) {
    var key = el.getAttribute('data-patitem');
    var campo = el.getAttribute('data-campo');
    if (!items[key]) items[key] = {};
    items[key][campo] = el.value;
  });
  var enfermedades = {};
  document.querySelectorAll('[data-patenf]').forEach(function(el) {
    var key = el.getAttribute('data-patenf');
    var campo = el.getAttribute('data-campo');
    if (!enfermedades[key]) enfermedades[key] = {};
    enfermedades[key][campo] = el.value;
  });
  return { items: items, enfermedades: enfermedades };
}

function recolectarExploracionFisica() {
  var ef = {};
  var secciones = {};
  document.querySelectorAll('[data-ef]').forEach(function(el) {
    var sec = el.getAttribute('data-ef');
    var campo = el.getAttribute('data-campo');
    if (!secciones[sec]) secciones[sec] = {};
    secciones[sec][campo] = el.value;
  });
  ef.cabezaCuello = secciones.cabezaCuello || {};
  ef.cabezaCuello.ojos = secciones.ojos || {};
  ef.cabezaCuello.oidos = secciones.oidos || {};
  ef.cabezaCuello.nariz = secciones.nariz || {};
  ef.cabezaCuello.boca = secciones.boca || {};
  ef.torax = secciones.torax || {};
  ef.abdomen = secciones.abdomen || {};
  ef.extremidades = { superiores: secciones.extSup || {}, inferiores: secciones.extInf || {} };
  ef.columna = secciones.columna || {};
  ef.pielAnexos = secciones.pielAnexos || {};
  ef.sistemaNervioso = secciones.sistemaNervioso || {};
  ef.genitourinarios = secciones.genitourinarios || {};
  ef.ginecoObstetricos = secciones.ginecoObstetricos || {};
  return ef;
}

function recolectarExamenesComplementarios() {
  var datos = {};
  document.querySelectorAll('[data-excomp]').forEach(function(el) {
    var key = el.getAttribute('data-excomp');
    var campo = el.getAttribute('data-campo');
    if (!datos[key]) datos[key] = {};
    datos[key][campo] = el.value;
  });
  return datos;
}

function recolectarResultados() {
  return {
    analisis: (document.querySelector('[data-seccion="resultados"][data-campo="analisis"]') || {}).value || '',
    diagnostico: [0,1,2,3,4].map(function(i) { return (document.getElementById('res-diagnostico-' + i) || {}).value || ''; }),
    recomendaciones: [0,1,2].map(function(i) { return (document.getElementById('res-recomendacion-' + i) || {}).value || ''; })
  };
}

// ==================== RESULTADO FINAL (APTO / NO APTO) ====================
function marcarResultado(resultado) {
  var comentario = document.getElementById('comentario-concluyente') ? document.getElementById('comentario-concluyente').value.trim() : '';
  if (!comentario) {
    showToast('Campo obligatorio', 'El comentario concluyente es obligatorio');
    var el = document.getElementById('comentario-concluyente');
    if (el) { el.focus(); el.style.borderColor = '#dc2626'; }
    return;
  }

  var textoConfirm = resultado === 'apto'
    ? 'Marcar como APTO avanzara al candidato a Entrevista con Jefe. Confirmar?'
    : 'Marcar como NO APTO rechazara al candidato del proceso. Confirmar?';
  if (!confirm(textoConfirm)) return;

  recolectarDatosFormulario();

  var examen = examenesMedicos.find(function(e) { return e.candidatoId === candidatoActualId && !e.resultado; });
  if (!examen) return;

  examen.resultado = resultado;
  examen.comentarioFinal = comentario;
  examen.realizadoPor = sesionSalud.userName || 'Salud Ocupacional';
  examen.fechaResultado = new Date().toISOString().split('T')[0];
  saveData();

  // Integracion con candidatos (localStorage compartido)
  candidatos = JSON.parse(localStorage.getItem('candidatos') || '[]');
  var candidato = candidatos.find(function(c) { return c.id === candidatoActualId; });

  if (candidato) {
    if (resultado === 'apto') {
      candidato.etapa = 'entrevista-jefe';
    } else {
      candidato.etapaRechazo = 'revision-medica';
      candidato.motivoRechazo = 'No apto medicamente: ' + comentario;
      candidato.etapa = 'rechazado';
    }

    if (!candidato.comentariosInternos) candidato.comentariosInternos = [];
    candidato.comentariosInternos.push({
      fecha: new Date().toISOString().split('T')[0],
      hora: new Date().toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' }),
      texto: '[Salud Ocupacional] Resultado: ' + (resultado === 'apto' ? 'APTO' : 'NO APTO') + '. ' + comentario,
      autor: sesionSalud.userName || 'Salud Ocupacional',
      tipo: 'salud'
    });

    candidato.comentarioSalud = {
      resultado: resultado,
      comentario: comentario,
      fecha: new Date().toISOString().split('T')[0],
      realizadoPor: sesionSalud.userName || 'Salud Ocupacional'
    };

    saveCandidatos();
  }

  detenerAutoGuardado();
  candidatoActualId = null;
  showToast(
    resultado === 'apto' ? 'Candidato APTO' : 'Candidato NO APTO',
    resultado === 'apto' ? 'El candidato avanza a Entrevista con Jefe' : 'El candidato ha sido rechazado en revision medica'
  );
  showView('expedientes');
}

// ==================== CABECERA DOCUMENTO SOC-REG-05 ====================
function buildCabeceraDocumento(pagina, totalPaginas) {
  var baseUrl = window.location.origin + '/portalgibanibb/';
  return '<div class="formato__section">' +
    '<div class="formato__col-1"><img src="' + baseUrl + 'img/gibanibb_logo.png"></div>' +
    '<div class="formato__col-2">' +
      '<div><span>TITULO: </span><p>EXAMEN MEDICO DE INGRESO</p></div>' +
      '<div><span>CLAVE: </span><p>SOC-REG-05</p></div>' +
      '<div><span>VERSION: </span><p>01</p></div>' +
    '</div>' +
    '<div class="formato__col-3">' +
      '<div><span>PAGINA: </span><p>' + pagina + ' de ' + totalPaginas + '</p></div>' +
      '<div><span>ULTIMA REVISION: </span><p>Febrero 2026</p></div>' +
      '<div><span>FECHA DE VIGENCIA: </span><p>Febrero 2027</p></div>' +
    '</div>' +
  '</div>';
}

// Helper: muestra valor o guion
function _v(val) { return escapeHtml(val || '') || '&mdash;'; }

// Helper: Si / No legible
function _sino(val) {
  if (val === 'si') return 'Si';
  if (val === 'no') return 'No';
  return '&mdash;';
}

// Helper: genera input editable para celdas del documento
function _fi(attrs, value, opts) {
  opts = opts || {};
  var type = opts.type || 'text';
  var ro = opts.readonly ? ' readonly tabindex="-1"' : '';
  var idAttr = opts.id ? ' id="' + opts.id + '"' : '';
  var ph = opts.placeholder ? ' placeholder="' + escapeHtml(opts.placeholder) + '"' : '';
  var oi = opts.oninput ? ' oninput="' + opts.oninput + '"' : '';
  var cls = 'doc-input' + (opts.readonly ? ' doc-input--readonly' : '');
  return '<input type="' + type + '" class="' + cls + '" ' + attrs + idAttr +
    ' value="' + escapeHtml(value || '') + '"' + ro + ph + oi + '>';
}

// Helper: genera select para celdas Si/No u opciones multiples
function _fsel(attrs, value, options) {
  options = options || [{v:'',t:'-'},{v:'si',t:'Si'},{v:'no',t:'No'}];
  var html = '<select class="doc-input doc-select" ' + attrs + '>';
  options.forEach(function(o) {
    html += '<option value="' + o.v + '"' + (value === o.v ? ' selected' : '') + '>' + o.t + '</option>';
  });
  return html + '</select>';
}

// ==================== RENDER FORMULARIO DOCUMENTO (editable, formato PDF) ====================
function renderFormularioDocumento(examen, candidato) {
  var v = vacantes.find(function(vac) { return vac.id === candidato.vacanteId; });
  var dp = examen.datos.datosPersonales || {};
  var al = examen.datos.antecedentesLaborales || {};
  var anp = examen.datos.antecedentesNoPatologicos || {};
  var hf = examen.datos.heredoFamiliares || {};
  var ap = examen.datos.antecedentesPatologicos || {};
  var sv = examen.datos.signosVitales || {};
  var sm = examen.datos.somatometria || {};
  var ef = examen.datos.exploracionFisica || {};
  var ec = examen.datos.examenesComplementarios || {};
  var res = examen.datos.resultados || {};

  var puestoCandidato = dp.puestoCandidato || (v ? v.titulo : '');

  // ========== PAGINA 1 ==========
  var pagina1 =
    buildCabeceraDocumento(1, 2) +

    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);">' +
      '<div style="width:50%;"><span class="doc-label">FECHA DE APLICACION: </span> ' + _fi('data-seccion="datosPersonales" data-campo="fechaAplicacion"', examen.fechaAplicacion, {type:'date'}) + '</div>' +
      '<div style="width:50%;"><span class="doc-label">PUESTO CANDIDATO: </span> ' + _fi('data-seccion="datosPersonales" data-campo="puestoCandidato"', puestoCandidato) + '</div>' +
    '</div>' +

    // 1. DATOS PERSONALES
    '<div class="doc-section-title">1. DATOS PERSONALES</div>' +
    '<div class="doc-row">' +
      '<div style="width:30%;"><span class="doc-label">NOMBRE: </span> ' + _fi('data-seccion="datosPersonales" data-campo="nombre"', dp.nombre || (candidato.nombre + ' ' + candidato.apellidos)) + '</div>' +
      '<div style="width:15%;"><span class="doc-label">EDAD: </span> ' + _fi('data-seccion="datosPersonales" data-campo="edad"', dp.edad, {type:'number'}) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">SEXO: </span> ' + _fsel('id="dp-sexo" data-seccion="datosPersonales" data-campo="sexo"', dp.sexo, [
        {v:'',t:'Sel.'},{v:'M',t:'Masculino'},{v:'F',t:'Femenino'}
      ]) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">EDO. CIVIL: </span> ' + _fsel('data-seccion="datosPersonales" data-campo="estadoCivil"', dp.estadoCivil, [
        {v:'',t:'Sel.'},{v:'soltero',t:'Soltero(a)'},{v:'casado',t:'Casado(a)'},{v:'union-libre',t:'Union Libre'},{v:'divorciado',t:'Divorciado(a)'},{v:'viudo',t:'Viudo(a)'}
      ]) + '</div>' +
      '<div style="width:15%;"><span class="doc-label">GRUPO/RH: </span> ' + _fi('data-seccion="datosPersonales" data-campo="grupoRH"', dp.grupoRH) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:30%;"><span class="doc-label">FECHA NAC.: </span> ' + _fi('data-seccion="datosPersonales" data-campo="fechaNacimiento"', dp.fechaNacimiento || candidato.fechaNacimiento, {type:'date'}) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">LUGAR NAC.: </span> ' + _fi('data-seccion="datosPersonales" data-campo="lugarNacimiento"', dp.lugarNacimiento) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">ESCOLARIDAD: </span> ' + _fi('data-seccion="datosPersonales" data-campo="escolaridad"', dp.escolaridad || candidato.escolaridad) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">NSS: </span> ' + _fi('data-seccion="datosPersonales" data-campo="nss"', dp.nss) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:40%;"><span class="doc-label">DOMICILIO: </span> ' + _fi('data-seccion="datosPersonales" data-campo="domicilio"', dp.domicilio) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TELEFONO: </span> ' + _fi('data-seccion="datosPersonales" data-campo="telefono"', dp.telefono || candidato.telefono) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TEL. EMERGENCIA: </span> ' + _fi('data-seccion="datosPersonales" data-campo="telefonoEmergencia"', dp.telefonoEmergencia) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CONTACTO EMERG.: </span> ' + _fi('data-seccion="datosPersonales" data-campo="contactoEmergencia"', dp.contactoEmergencia) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:50%;"><span class="doc-label">CLINICA ADSCRIPCION: </span> ' + _fi('data-seccion="datosPersonales" data-campo="clinicaAdscripcion"', dp.clinicaAdscripcion) + '</div>' +
      '<div style="width:50%;"><span class="doc-label">REALIZADO POR: </span> ' + _fi('', examen.realizadoPor, {readonly:true}) + '</div>' +
    '</div>';

  // 2. ANTECEDENTES LABORALES
  var agentesNombres = ['Quimicos','Solventes','Insecticidas','Soldaduras met./quim.','Bacterias, Hongos','Vapores/Humos','Polvos/Gases','Ruidos','Vibraciones','T menor a 10','T mayor a 37','Carga de peso (kg)','Rotacion de turno'];
  var filasAgentes = '';
  agentesNombres.forEach(function(nombre, i) {
    var key = 'agente_' + i;
    var ag = (al.agentes && al.agentes[key]) || {};
    filasAgentes +=
      '<div class="doc-row">' +
        '<div style="width:22%;text-align:left;padding-left:6px;">' + nombre + '</div>' +
        '<div style="width:8%;">' + _fsel('data-agente="' + key + '" data-campo="expuesto"', ag.expuesto) + '</div>' +
        '<div style="width:14%;">' + _fi('data-agente="' + key + '" data-campo="duracion"', ag.duracion) + '</div>' +
        '<div style="width:14%;">' + _fi('data-agente="' + key + '" data-campo="epp"', ag.epp) + '</div>' +
        '<div style="width:14%;">' + _fi('data-agente="' + key + '" data-campo="capacitacion"', ag.capacitacion) + '</div>' +
        '<div style="width:28%;">' + _fi('data-agente="' + key + '" data-campo="empresa"', ag.empresa) + '</div>' +
      '</div>';
  });

  var riesgo = al.riesgoTrabajo || {};
  var enfLab = al.enfermedadLaboral || {};

  pagina1 +=
    '<div class="doc-section-title">2. ANTECEDENTES LABORALES</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:22%;">Agente</div>' +
      '<div style="width:8%;">Si/No</div>' +
      '<div style="width:14%;">Duracion</div>' +
      '<div style="width:14%;">EPP</div>' +
      '<div style="width:14%;">Capacitacion</div>' +
      '<div style="width:28%;">Empresa / Obs.</div>' +
    '</div>' +
    filasAgentes +
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);">' +
      '<div style="width:25%;"><span class="doc-label">RIESGO TRAB. TIPO: </span> ' + _fsel('data-seccion="riesgoTrabajo" data-campo="tipo"', riesgo.tipo, [
        {v:'',t:'-'},{v:'imss',t:'IMSS'},{v:'particular',t:'Particular'}
      ]) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">CALIF.: </span> ' + _fsel('data-seccion="riesgoTrabajo" data-campo="calificacion"', riesgo.calificacion, [
        {v:'',t:'-'},{v:'si-trabajo',t:'Si (Trabajo)'},{v:'no',t:'No'}
      ]) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">LESION: </span> ' + _fi('data-seccion="riesgoTrabajo" data-campo="lesion"', riesgo.lesion) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DIAS: </span> ' + _fi('data-seccion="riesgoTrabajo" data-campo="diasOtorgados"', riesgo.diasOtorgados) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">ENF. LABORAL: </span> ' + _fsel('data-seccion="enfermedadLaboral" data-campo="calificacion"', enfLab.calificacion, [
        {v:'',t:'-'},{v:'si',t:'Si'},{v:'no',t:'No'}
      ]) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">LESION/PAT.: </span> ' + _fi('data-seccion="enfermedadLaboral" data-campo="lesionPatologia"', enfLab.lesionPatologia) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DIAS: </span> ' + _fi('data-seccion="enfermedadLaboral" data-campo="diasOtorgados"', enfLab.diasOtorgados) + '</div>' +
      '<div style="width:25%;">&nbsp;</div>' +
    '</div>';

  // 3. ANTECEDENTES NO PATOLOGICOS
  var vac = anp.vacunas || {};
  pagina1 +=
    '<div class="doc-section-title">3. ANTECEDENTES NO PATOLOGICOS</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">ALCOHOL: </span> ' + _fsel('data-seccion="antecedentesNoPatologicos" data-campo="alcohol"', anp.alcohol) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">FRECUENCIA: </span> ' + _fi('data-seccion="antecedentesNoPatologicos" data-campo="alcoholDesc"', anp.alcoholDesc) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TABACO: </span> ' + _fsel('data-seccion="antecedentesNoPatologicos" data-campo="tabaco"', anp.tabaco) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">FRECUENCIA: </span> ' + _fi('data-seccion="antecedentesNoPatologicos" data-campo="tabacoDesc"', anp.tabacoDesc) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">SUSTANCIAS: </span> ' + _fsel('data-seccion="antecedentesNoPatologicos" data-campo="sustancias"', anp.sustancias) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">FRECUENCIA: </span> ' + _fi('data-seccion="antecedentesNoPatologicos" data-campo="sustanciasDesc"', anp.sustanciasDesc) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">DEPORTE: </span> ' + _fsel('data-seccion="antecedentesNoPatologicos" data-campo="deporte"', anp.deporte) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">CUAL: </span> ' + _fi('data-seccion="antecedentesNoPatologicos" data-campo="deporteDesc"', anp.deporteDesc) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:16%;"><span class="doc-label">TETANOS: </span> ' + _fsel('data-seccion="vacTetanos" data-campo="tetanos"', vac.tetanos) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">INFLUENZA: </span> ' + _fsel('data-seccion="vacInfluenza" data-campo="influenza"', vac.influenza) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">HEPATITIS B: </span> ' + _fsel('data-seccion="vacHepatitisB" data-campo="hepatitisB"', vac.hepatitisB) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">COVID: </span> ' + _fsel('data-seccion="vacCovid" data-campo="covid"', vac.covid) + '</div>' +
      '<div style="width:18%;"><span class="doc-label">DESPARASIT.: </span> ' + _fsel('data-seccion="antecedentesNoPatologicos" data-campo="desparasitacion"', anp.desparasitacion) + '</div>' +
      '<div style="width:18%;"><span class="doc-label">ALERGIAS: </span> ' + _fi('data-seccion="antecedentesNoPatologicos" data-campo="alergias"', anp.alergias) + '</div>' +
    '</div>';

  // 4. ANTECEDENTES HEREDO-FAMILIARES
  var hfEnfermedades = ['Hipertension','Enf. Cardiovasculares','Diabetes Mellitus','Enf. Renales','Osteoartritis','Cancer','Enf. Psiquiatricas','Otros'];
  var hfFamiliares = ['Madre','Padre','Hermanos','Abuelos','Hijos','Complicaciones'];
  var filasHF = '';
  hfEnfermedades.forEach(function(enf, i) {
    var key = 'enf_' + i;
    var vals = (hf.tabla && hf.tabla[key]) || {};
    var celdas = '';
    hfFamiliares.forEach(function(fam, j) {
      var fkey = 'fam_' + j;
      var w = j === 5 ? '18%' : '10%';
      if (j === 5) {
        celdas += '<div style="width:' + w + ';">' + _fi('data-hf="' + key + '" data-fam="' + fkey + '"', vals[fkey]) + '</div>';
      } else {
        celdas += '<div style="width:' + w + ';">' + _fsel('data-hf="' + key + '" data-fam="' + fkey + '"', vals[fkey]) + '</div>';
      }
    });
    filasHF += '<div class="doc-row"><div style="width:22%;text-align:left;padding-left:6px;">' + enf + '</div>' + celdas + '</div>';
  });

  pagina1 +=
    '<div class="doc-section-title">4. ANTECEDENTES HEREDO-FAMILIARES</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:22%;">Enfermedad</div>' +
      '<div style="width:10%;">Madre</div>' +
      '<div style="width:10%;">Padre</div>' +
      '<div style="width:10%;">Hermanos</div>' +
      '<div style="width:10%;">Abuelos</div>' +
      '<div style="width:10%;">Hijos</div>' +
      '<div style="width:18%;">Complicaciones</div>' +
    '</div>' +
    filasHF;

  // ========== PAGINA 2 ==========
  // 5. ANTECEDENTES PATOLOGICOS
  var apItems = ['Hospitalizaciones','Interv. quirurgicas','Transfusiones','Fracturas','Esguinces','Luxaciones'];
  var filasApItems = '';
  apItems.forEach(function(item, i) {
    var key = 'item_' + i;
    var val = (ap.items && ap.items[key]) || {};
    filasApItems +=
      '<div class="doc-row">' +
        '<div style="width:30%;text-align:left;padding-left:6px;">' + item + '</div>' +
        '<div style="width:15%;">' + _fsel('data-patitem="' + key + '" data-campo="tiene"', val.tiene) + '</div>' +
        '<div style="width:55%;">' + _fi('data-patitem="' + key + '" data-campo="motivo"', val.motivo) + '</div>' +
      '</div>';
  });

  var enfIzqNombres = ['Hipertension Arterial','Diabetes Mellitus','Enf. Cardiovasculares','Enf. Renales','Enf. Gastrointestinales','Artritis','Osteoartritis','Cancer'];
  var enfDerNombres = ['EPOC','Asma','Neumonias','Fibromialgias','Enf. Autoinmunes','Enf. Psiquiatricas','Convulsiones/Epilepsias','Otros'];

  var filasEnfIzq = '';
  enfIzqNombres.forEach(function(enf, i) {
    var key = 'enf_' + i;
    var val = (ap.enfermedades && ap.enfermedades[key]) || {};
    filasEnfIzq +=
      '<div class="doc-row">' +
        '<div style="width:40%;text-align:left;padding-left:6px;">' + enf + '</div>' +
        '<div style="width:15%;">' + _fsel('data-patenf="' + key + '" data-campo="tiene"', val.tiene) + '</div>' +
        '<div style="width:45%;">' + _fi('data-patenf="' + key + '" data-campo="tratamiento"', val.tratamiento) + '</div>' +
      '</div>';
  });

  var filasEnfDer = '';
  enfDerNombres.forEach(function(enf, i) {
    var key = 'enf_' + (i + 8);
    var val = (ap.enfermedades && ap.enfermedades[key]) || {};
    filasEnfDer +=
      '<div class="doc-row">' +
        '<div style="width:40%;text-align:left;padding-left:6px;">' + enf + '</div>' +
        '<div style="width:15%;">' + _fsel('data-patenf="' + key + '" data-campo="tiene"', val.tiene) + '</div>' +
        '<div style="width:45%;">' + _fi('data-patenf="' + key + '" data-campo="tratamiento"', val.tratamiento) + '</div>' +
      '</div>';
  });

  var pagina2 =
    buildCabeceraDocumento(2, 2) +

    '<div class="doc-section-title">5. ANTECEDENTES PATOLOGICOS</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:30%;"></div>' +
      '<div style="width:15%;">Si/No</div>' +
      '<div style="width:55%;">Motivo / Edad / Observacion</div>' +
    '</div>' +
    filasApItems +

    '<div class="doc-row-header" style="margin-top:0.3rem;">' +
      '<div style="width:50%;">Enfermedad / Sistema</div>' +
      '<div style="width:50%;">Enfermedad / Sistema</div>' +
    '</div>' +
    '<div style="display:flex;width:100%;">' +
      '<div style="width:50%;">' +
        '<div class="doc-row-header"><div style="width:40%;">Enfermedad</div><div style="width:15%;">Si/No</div><div style="width:45%;">Tratamiento</div></div>' +
        filasEnfIzq +
      '</div>' +
      '<div style="width:50%;">' +
        '<div class="doc-row-header"><div style="width:40%;">Enfermedad</div><div style="width:15%;">Si/No</div><div style="width:45%;">Tratamiento</div></div>' +
        filasEnfDer +
      '</div>' +
    '</div>';

  // 6. SIGNOS VITALES + SOMATOMETRIA
  pagina2 +=
    '<div class="doc-section-title">6. SIGNOS VITALES Y SOMATOMETRIA</div>' +
    '<div class="doc-row">' +
      '<div style="width:16%;"><span class="doc-label">T/A: </span> ' + _fi('data-seccion="signosVitales" data-campo="ta"', sv.ta) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">FC: </span> ' + _fi('data-seccion="signosVitales" data-campo="fc"', sv.fc) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">FR: </span> ' + _fi('data-seccion="signosVitales" data-campo="fr"', sv.fr) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">TEMP: </span> ' + _fi('data-seccion="signosVitales" data-campo="temp"', sv.temp) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">SpO2: </span> ' + _fi('data-seccion="signosVitales" data-campo="spo2"', sv.spo2) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">PESO: </span> ' + _fi('data-seccion="somatometria" data-campo="peso"', sm.peso, {id:'sm-peso', oninput:'calcularIMC()'}) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">TALLA: </span> ' + _fi('data-seccion="somatometria" data-campo="talla"', sm.talla, {id:'sm-talla', oninput:'calcularIMC()'}) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">IMC: </span> ' + _fi('data-seccion="somatometria" data-campo="imc"', sm.imc, {id:'sm-imc', readonly:true}) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CINTURA: </span> ' + _fi('data-seccion="somatometria" data-campo="cintura"', sm.cintura) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CADERA: </span> ' + _fi('data-seccion="somatometria" data-campo="cadera"', sm.cadera) + '</div>' +
      '<div style="width:40%;">&nbsp;</div>' +
    '</div>';

  // 7. EXPLORACION FISICA (Parte 1)
  var cc = ef.cabezaCuello || {};
  var ojos = cc.ojos || {};
  var oidos = cc.oidos || {};
  var nariz = cc.nariz || {};
  var boca = cc.boca || {};
  var tor = ef.torax || {};
  var abd = ef.abdomen || {};
  var ext = ef.extremidades || {};
  var sup = ext.superiores || {};
  var inf = ext.inferiores || {};
  var col = ef.columna || {};

  pagina2 +=
    '<div class="doc-section-title">7. EXPLORACION FISICA</div>' +

    // Ojos
    '<div class="doc-row-header"><div style="width:30%;">OJOS</div><div style="width:35%;">Ojo Derecho</div><div style="width:35%;">Ojo Izquierdo</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Agudeza visual</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="agudezaDer"', ojos.agudezaDer) + '</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="agudezaIzq"', ojos.agudezaIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Ident. de color</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="colorDer"', ojos.colorDer) + '</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="colorIzq"', ojos.colorIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Mov. oculares</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="movimientosDer"', ojos.movimientosDer) + '</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="movimientosIzq"', ojos.movimientosIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Campo visual</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="campoVisualDer"', ojos.campoVisualDer) + '</div><div style="width:35%;">' + _fi('data-ef="ojos" data-campo="campoVisualIzq"', ojos.campoVisualIzq) + '</div></div>' +

    // Oidos
    '<div class="doc-row-header" style="margin-top:0.2rem;"><div style="width:30%;">OIDOS</div><div style="width:35%;">Oido Derecho</div><div style="width:35%;">Oido Izquierdo</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Conducto interno</div><div style="width:35%;">' + _fi('data-ef="oidos" data-campo="conductoInternoDer"', oidos.conductoInternoDer) + '</div><div style="width:35%;">' + _fi('data-ef="oidos" data-campo="conductoInternoIzq"', oidos.conductoInternoIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Conducto externo</div><div style="width:35%;">' + _fi('data-ef="oidos" data-campo="conductoExternoDer"', oidos.conductoExternoDer) + '</div><div style="width:35%;">' + _fi('data-ef="oidos" data-campo="conductoExternoIzq"', oidos.conductoExternoIzq) + '</div></div>' +

    // Nariz, Faringe, Tiroides, Boca
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:25%;"><span class="doc-label">NARIZ CONF.: </span> ' + _fi('data-ef="nariz" data-campo="conformacion"', nariz.conformacion) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">TABIQUE: </span> ' + _fi('data-ef="nariz" data-campo="tabique"', nariz.tabique) + '</div>' +
      '<div style="width:50%;"><span class="doc-label">FARINGE/AMIGDALAS: </span> ' + _fi('data-ef="cabezaCuello" data-campo="faringeAmigdalas"', cc.faringeAmigdalas) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">TIROIDES: </span> ' + _fsel('data-ef="cabezaCuello" data-campo="tiroides"', cc.tiroides, [
        {v:'',t:'-'},{v:'normal',t:'Normal'},{v:'aumentado',t:'Aumentado'},{v:'nodulos',t:'Nodulos'}
      ]) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">ADENOPATIAS: </span> ' + _fsel('data-ef="cabezaCuello" data-campo="adenopatias"', cc.adenopatias) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DOLOROSAS: </span> ' + _fsel('data-ef="cabezaCuello" data-campo="dolorosas"', cc.dolorosas) + '</div>' +
      '<div style="width:25%;">&nbsp;</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">DENTADURA: </span> ' + _fi('data-ef="boca" data-campo="dentadura"', boca.dentadura) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">FALTANTES: </span> ' + _fi('data-ef="boca" data-campo="faltantes"', boca.faltantes) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TRATAM.: </span> ' + _fi('data-ef="boca" data-campo="tratamientos"', boca.tratamientos) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CARIES: </span> ' + _fi('data-ef="boca" data-campo="caries"', boca.caries) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">LENGUA: </span> ' + _fi('data-ef="boca" data-campo="lengua"', boca.lengua) + '</div>' +
    '</div>' +

    // Torax
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">TORAX</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">CONFORM.: </span> ' + _fsel('data-ef="torax" data-campo="conformacion"', tor.conformacion, [
        {v:'',t:'-'},{v:'normolineo',t:'Normolineo'},{v:'brevilineo',t:'Brevilineo'},{v:'longilinio',t:'Longilinio'}
      ]) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">AMPLEXION: </span> ' + _fsel('data-ef="torax" data-campo="amplexion"', tor.amplexion, [{v:'',t:'-'},{v:'normal',t:'Normal'},{v:'anormal',t:'Anormal'}]) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">AMPLEXACION: </span> ' + _fsel('data-ef="torax" data-campo="amplexacion"', tor.amplexacion, [{v:'',t:'-'},{v:'normal',t:'Normal'},{v:'anormal',t:'Anormal'}]) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">MURM. VES.: </span> ' + _fsel('data-ef="torax" data-campo="murmulloVesicular"', tor.murmulloVesicular, [{v:'',t:'-'},{v:'normal',t:'Normal'},{v:'anormal',t:'Anormal'}]) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">ESTERTORES: </span> ' + _fi('data-ef="torax" data-campo="estertores"', tor.estertores) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">R. CARDIACOS: </span> ' + _fsel('data-ef="torax" data-campo="ruidosCardiacos"', tor.ruidosCardiacos, [{v:'',t:'-'},{v:'normal',t:'Normal'},{v:'anormal',t:'Anormal'}]) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:100%;"><span class="doc-label">PULSOS: </span> ' + _fi('data-ef="torax" data-campo="pulsos"', tor.pulsos) + '</div>' +
    '</div>' +

    // Abdomen
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">ABDOMEN</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">CONFORM.: </span> ' + _fi('data-ef="abdomen" data-campo="conformacion"', abd.conformacion) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">PARED: </span> ' + _fi('data-ef="abdomen" data-campo="pared"', abd.pared) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">PERISTALSIS: </span> ' + _fsel('data-ef="abdomen" data-campo="peristalsis"', abd.peristalsis, [{v:'',t:'-'},{v:'normal',t:'Normal'},{v:'aumentada',t:'Aumentada'},{v:'disminuida',t:'Disminuida'}]) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">HIGADO: </span> ' + _fsel('data-ef="abdomen" data-campo="higado"', abd.higado) + '</div>' +
      '<div style="width:13%;"><span class="doc-label">BAZO: </span> ' + _fsel('data-ef="abdomen" data-campo="bazo"', abd.bazo) + '</div>' +
      '<div style="width:13%;"><span class="doc-label">MASAS TUM.: </span> ' + _fsel('data-ef="abdomen" data-campo="masasTumorales"', abd.masasTumorales) + '</div>' +
    '</div>' +

    // Extremidades
    '<div class="doc-row-header" style="margin-top:0.2rem;">' +
      '<div style="width:18%;">EXTREMIDADES</div>' +
      '<div style="width:16%;">Simetria</div>' +
      '<div style="width:16%;">Movilidad</div>' +
      '<div style="width:16%;">Fuerza</div>' +
      '<div style="width:18%;">Articulaciones</div>' +
      '<div style="width:16%;">Varices</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:18%;font-weight:600;">Superiores</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extSup" data-campo="simetria"', sup.simetria) + '</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extSup" data-campo="movilidad"', sup.movilidad) + '</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extSup" data-campo="fuerza"', sup.fuerza) + '</div>' +
      '<div style="width:18%;">' + _fi('data-ef="extSup" data-campo="articulaciones"', sup.articulaciones) + '</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extSup" data-campo="varices"', sup.varices) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:18%;font-weight:600;">Inferiores</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extInf" data-campo="simetria"', inf.simetria) + '</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extInf" data-campo="movilidad"', inf.movilidad) + '</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extInf" data-campo="fuerza"', inf.fuerza) + '</div>' +
      '<div style="width:18%;">' + _fi('data-ef="extInf" data-campo="articulaciones"', inf.articulaciones) + '</div>' +
      '<div style="width:16%;">' + _fi('data-ef="extInf" data-campo="varices"', inf.varices) + '</div>' +
    '</div>' +

    // Columna Vertebral
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">COLUMNA VERTEBRAL</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:18%;"><span class="doc-label">MARCAS CONG.: </span> ' + _fi('data-ef="columna" data-campo="marcasCongenitas"', col.marcasCongenitas) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">LORDOSIS: </span> ' + _fi('data-ef="columna" data-campo="lordosis"', col.lordosis) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">ESCOLIOSIS: </span> ' + _fi('data-ef="columna" data-campo="escoliosis"', col.escoliosis) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">MOVILIDAD: </span> ' + _fi('data-ef="columna" data-campo="movilidad"', col.movilidad) + '</div>' +
      '<div style="width:18%;"><span class="doc-label">P. DOLOROSOS: </span> ' + _fi('data-ef="columna" data-campo="puntosDolorosos"', col.puntosDolorosos) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">MARCHA P-T: </span> ' + _fi('data-ef="columna" data-campo="marchaPuntaTalon"', col.marchaPuntaTalon) + '</div>' +
    '</div>';

  // ========== CONTINUACION PAGINA 2 (Piel, Sist. Nervioso, Genitourinarios, Gineco, Examenes, Resultados) ==========
  var piel = ef.pielAnexos || {};
  var sn = ef.sistemaNervioso || {};
  var gu = ef.genitourinarios || {};
  var go = ef.ginecoObstetricos || {};

  pagina2 +=

    // Piel y Anexos
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">PIEL Y ANEXOS</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">DERMATOSIS: </span> ' + _fi('data-ef="pielAnexos" data-campo="dermatosis"', piel.dermatosis) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DERMATITIS: </span> ' + _fi('data-ef="pielAnexos" data-campo="dermatitis"', piel.dermatitis) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">PSORIASIS: </span> ' + _fi('data-ef="pielAnexos" data-campo="psoriasis"', piel.psoriasis) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">INF. CUTANEA: </span> ' + _fi('data-ef="pielAnexos" data-campo="infeccionCutanea"', piel.infeccionCutanea) + '</div>' +
    '</div>' +

    // Sistema Nervioso
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">SISTEMA NERVIOSO</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">CONDUCTA: </span> ' + _fi('data-ef="sistemaNervioso" data-campo="conducta"', sn.conducta) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">LENGUAJE: </span> ' + _fi('data-ef="sistemaNervioso" data-campo="lenguaje"', sn.lenguaje) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">MOTRICIDAD: </span> ' + _fi('data-ef="sistemaNervioso" data-campo="motricidad"', sn.motricidad) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">OTROS: </span> ' + _fi('data-ef="sistemaNervioso" data-campo="otros"', sn.otros) + '</div>' +
    '</div>' +

    // Genitourinarios
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">GENITOURINARIOS</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">TRAST. INFLAM.: </span> ' + _fi('data-ef="genitourinarios" data-campo="trastornoInflamatorio"', gu.trastornoInflamatorio) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">INFECCIONES: </span> ' + _fi('data-ef="genitourinarios" data-campo="infecciones"', gu.infecciones) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">COND. NEOPL.: </span> ' + _fi('data-ef="genitourinarios" data-campo="condicionesNeoplasicas"', gu.condicionesNeoplasicas) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">OBSTRUCCIONES: </span> ' + _fi('data-ef="genitourinarios" data-campo="obstrucciones"', gu.obstrucciones) + '</div>' +
    '</div>';

  // Gineco-Obstetricos (visible solo si sexo es F)
  pagina2 +=
    '<div id="ef-gineco-section"' + (dp.sexo === 'F' ? '' : ' style="display:none;"') + '>' +
      '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
        '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">GINECO-OBSTETRICOS</div>' +
      '</div>' +
      '<div class="doc-row">' +
        '<div style="width:20%;"><span class="doc-label">FUM: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="fum"', go.fum, {type:'date'}) + '</div>' +
        '<div style="width:20%;"><span class="doc-label">MENARCA: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="menarca"', go.menarca) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">GESTACIONES: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="gestaciones"', go.gestaciones) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">DISTOCICO: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="distocico"', go.distocico) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">EUTOCICO: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="eutocico"', go.eutocico) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">ABORTO: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="aborto"', go.aborto) + '</div>' +
      '</div>' +
      '<div class="doc-row">' +
        '<div style="width:25%;"><span class="doc-label">FUP: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="fup"', go.fup, {type:'date'}) + '</div>' +
        '<div style="width:25%;"><span class="doc-label">MENOPAUSIA: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="menopausia"', go.menopausia) + '</div>' +
        '<div style="width:25%;"><span class="doc-label">METODO P.F.: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="metodoPlanificacion"', go.metodoPlanificacion) + '</div>' +
        '<div style="width:25%;"><span class="doc-label">ENFERMEDADES: </span> ' + _fi('data-ef="ginecoObstetricos" data-campo="enfermedades"', go.enfermedades) + '</div>' +
      '</div>' +
    '</div>';

  // 8. EXAMENES COMPLEMENTARIOS
  var examenesComp = [
    { key: 'biometriaHematica', label: 'Biometria Hematica' },
    { key: 'orina', label: 'Examen de Orina' },
    { key: 'quimicaSanguinea', label: 'Quimica Sanguinea' },
    { key: 'antidoping', label: 'Antidoping' },
    { key: 'espirometria', label: 'Espirometria' },
    { key: 'audiometria', label: 'Audiometria' },
    { key: 'coprocultivo', label: 'Coprocultivo' }
  ];

  var filasExComp = '';
  examenesComp.forEach(function(ex) {
    var val = ec[ex.key] || {};
    filasExComp +=
      '<div class="doc-row">' +
        '<div style="width:30%;text-align:left;padding-left:6px;">' + ex.label + '</div>' +
        '<div style="width:15%;">' + _fsel('data-excomp="' + ex.key + '" data-campo="aplica"', val.aplica) + '</div>' +
        '<div style="width:55%;">' + _fi('data-excomp="' + ex.key + '" data-campo="resultados"', val.resultados) + '</div>' +
      '</div>';
  });

  pagina2 +=
    '<div class="doc-section-title">8. EXAMENES COMPLEMENTARIOS</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:30%;">Examen</div>' +
      '<div style="width:15%;">Aplica</div>' +
      '<div style="width:55%;">Resultados</div>' +
    '</div>' +
    filasExComp;

  // Firma del candidato
  pagina2 += '<div class="doc-firma-line">FIRMA DEL CANDIDATO</div>';

  // RESULTADOS
  pagina2 +=
    '<div class="doc-section-title">RESULTADOS</div>' +
    '<div class="doc-row">' +
      '<div style="width:100%;min-height:2.5rem;text-align:left;padding:4px 6px;">' +
        '<span class="doc-label">ANALISIS GENERAL: </span><br>' +
        '<textarea id="res-analisis" class="doc-input doc-textarea" data-seccion="resultados" data-campo="analisis" rows="2">' + escapeHtml(res.analisis || '') + '</textarea>' +
      '</div>' +
    '</div>';

  // Diagnosticos
  var diagnosticos = res.diagnostico || [];
  for (var di = 0; di < 5; di++) {
    pagina2 +=
      '<div class="doc-row">' +
        '<div style="width:100%;text-align:left;padding-left:6px;"><span class="doc-label">DIAGNOSTICO ' + (di + 1) + ': </span> ' + _fi('id="res-diagnostico-' + di + '"', diagnosticos[di]) + '</div>' +
      '</div>';
  }

  // Recomendaciones
  var recomendaciones = res.recomendaciones || [];
  for (var ri = 0; ri < 3; ri++) {
    pagina2 +=
      '<div class="doc-row">' +
        '<div style="width:100%;text-align:left;padding-left:6px;"><span class="doc-label">RECOMENDACION ' + (ri + 1) + ': </span> ' + _fi('id="res-recomendacion-' + ri + '"', recomendaciones[ri]) + '</div>' +
      '</div>';
  }

  // Comentario concluyente
  pagina2 +=
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.3rem;">' +
      '<div style="width:100%;min-height:2.5rem;text-align:left;padding:4px 6px;">' +
        '<span class="doc-label">COMENTARIO CONCLUYENTE: </span><br>' +
        '<textarea id="comentario-concluyente" class="doc-input doc-textarea" rows="2" placeholder="Escriba su comentario concluyente aqui...">' + escapeHtml(examen.comentarioFinal || '') + '</textarea>' +
      '</div>' +
    '</div>';

  // Botones APTO / NO APTO
  pagina2 +=
    '<div class="resultado-botones">' +
      '<button type="button" class="btn-apto" onclick="marcarResultado(\'apto\')"><i class="fas fa-check-circle" style="margin-right:8px;"></i>APTO</button>' +
      '<button type="button" class="btn-no-apto" onclick="marcarResultado(\'no-apto\')"><i class="fas fa-times-circle" style="margin-right:8px;"></i>NO APTO</button>' +
    '</div>';

  // Ensamblar las paginas
  var html =
    '<div class="pdf-container--print">' +
      '<div class="page__container">' + pagina1 + '</div>' +
      '<div class="page-break"></div>' +
      '<div class="page__container">' + pagina2 + '</div>' +
    '</div>';

  document.getElementById('form-doc-container').innerHTML = html;

  // Calcular IMC si ya hay datos
  if (sm.peso && sm.talla) setTimeout(calcularIMC, 100);
}

// ==================== VER DETALLE EXAMEN (formato PDF) ====================
function verDetalleExamen(examenId) {
  recargarDatos();
  var examen = examenesMedicos.find(function(e) { return e.id === examenId; });
  if (!examen) return;
  var candidato = candidatos.find(function(c) { return c.id === examen.candidatoId; });
  if (!candidato) return;
  var v = vacantes.find(function(vac) { return vac.id === candidato.vacanteId; });

  document.getElementById('detalle-titulo').textContent = 'SOC-REG-05 - ' + candidato.nombre + ' ' + candidato.apellidos;

  var dp = examen.datos.datosPersonales || {};
  var al = examen.datos.antecedentesLaborales || {};
  var anp = examen.datos.antecedentesNoPatologicos || {};
  var hf = examen.datos.heredoFamiliares || {};
  var ap = examen.datos.antecedentesPatologicos || {};
  var sv = examen.datos.signosVitales || {};
  var sm = examen.datos.somatometria || {};
  var ef = examen.datos.exploracionFisica || {};
  var ec = examen.datos.examenesComplementarios || {};
  var res = examen.datos.resultados || {};

  // ========== PAGINA 1 ==========
  var puestoCandidato = dp.puestoCandidato || (v ? v.titulo : '');

  // Datos Personales
  var sexoLabel = dp.sexo === 'M' ? 'Masculino' : dp.sexo === 'F' ? 'Femenino' : '';
  var estadoCivilMap = { soltero:'Soltero(a)', casado:'Casado(a)', 'union-libre':'Union Libre', divorciado:'Divorciado(a)', viudo:'Viudo(a)' };
  var estadoCivilLabel = estadoCivilMap[dp.estadoCivil] || dp.estadoCivil || '';

  var pagina1 =
    buildCabeceraDocumento(1, 2) +

    // Fila superior: Fecha / Puesto
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);">' +
      '<div style="width:50%;"><span class="doc-label">FECHA DE APLICACION: </span> ' + _v(formatFecha(examen.fechaAplicacion)) + '</div>' +
      '<div style="width:50%;"><span class="doc-label">PUESTO CANDIDATO: </span> ' + _v(puestoCandidato) + '</div>' +
    '</div>' +

    // 1. DATOS PERSONALES
    '<div class="doc-section-title">1. DATOS PERSONALES</div>' +
    '<div class="doc-row">' +
      '<div style="width:30%;"><span class="doc-label">NOMBRE: </span> ' + _v(dp.nombre) + '</div>' +
      '<div style="width:15%;"><span class="doc-label">EDAD: </span> ' + _v(dp.edad) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">SEXO: </span> ' + _v(sexoLabel) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">EDO. CIVIL: </span> ' + _v(estadoCivilLabel) + '</div>' +
      '<div style="width:15%;"><span class="doc-label">GRUPO/RH: </span> ' + _v(dp.grupoRH) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:30%;"><span class="doc-label">FECHA NAC.: </span> ' + _v(formatFecha(dp.fechaNacimiento)) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">LUGAR NAC.: </span> ' + _v(dp.lugarNacimiento) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">ESCOLARIDAD: </span> ' + _v(dp.escolaridad) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">NSS: </span> ' + _v(dp.nss) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:40%;"><span class="doc-label">DOMICILIO: </span> ' + _v(dp.domicilio) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TELEFONO: </span> ' + _v(dp.telefono) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TEL. EMERGENCIA: </span> ' + _v(dp.telefonoEmergencia) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CONTACTO EMERG.: </span> ' + _v(dp.contactoEmergencia) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:50%;"><span class="doc-label">CLINICA ADSCRIPCION: </span> ' + _v(dp.clinicaAdscripcion) + '</div>' +
      '<div style="width:50%;"><span class="doc-label">REALIZADO POR: </span> ' + _v(examen.realizadoPor) + '</div>' +
    '</div>';

  // 2. ANTECEDENTES LABORALES
  var agentesNombres = ['Quimicos','Solventes','Insecticidas','Soldaduras met./quim.','Bacterias, Hongos','Vapores/Humos','Polvos/Gases','Ruidos','Vibraciones','T menor a 10','T mayor a 37','Carga de peso (kg)','Rotacion de turno'];
  var filasAgentes = '';
  agentesNombres.forEach(function(nombre, i) {
    var key = 'agente_' + i;
    var ag = (al.agentes && al.agentes[key]) || {};
    filasAgentes +=
      '<div class="doc-row">' +
        '<div style="width:22%;text-align:left;padding-left:6px;">' + nombre + '</div>' +
        '<div style="width:8%;">' + _sino(ag.expuesto) + '</div>' +
        '<div style="width:14%;">' + _v(ag.duracion) + '</div>' +
        '<div style="width:14%;">' + _v(ag.epp) + '</div>' +
        '<div style="width:14%;">' + _v(ag.capacitacion) + '</div>' +
        '<div style="width:28%;">' + _v(ag.empresa) + '</div>' +
      '</div>';
  });

  var riesgo = al.riesgoTrabajo || {};
  var enfLab = al.enfermedadLaboral || {};

  pagina1 +=
    '<div class="doc-section-title">2. ANTECEDENTES LABORALES</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:22%;">Agente</div>' +
      '<div style="width:8%;">Si/No</div>' +
      '<div style="width:14%;">Duracion</div>' +
      '<div style="width:14%;">EPP</div>' +
      '<div style="width:14%;">Capacitacion</div>' +
      '<div style="width:28%;">Empresa / Obs.</div>' +
    '</div>' +
    filasAgentes +
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);">' +
      '<div style="width:50%;"><span class="doc-label">RIESGO DE TRABAJO: </span> ' + _v(riesgo.tipo) + ' / ' + _v(riesgo.calificacion) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">LESION: </span> ' + _v(riesgo.lesion) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DIAS: </span> ' + _v(riesgo.diasOtorgados) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:50%;"><span class="doc-label">ENF. LABORAL: </span> ' + _v(enfLab.calificacion) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">LESION/PATOLOGIA: </span> ' + _v(enfLab.lesionPatologia) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DIAS: </span> ' + _v(enfLab.diasOtorgados) + '</div>' +
    '</div>';

  // 3. ANTECEDENTES NO PATOLOGICOS
  var vac = anp.vacunas || {};
  pagina1 +=
    '<div class="doc-section-title">3. ANTECEDENTES NO PATOLOGICOS</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">ALCOHOL: </span> ' + _sino(anp.alcohol) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">FRECUENCIA: </span> ' + _v(anp.alcoholDesc) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TABACO: </span> ' + _sino(anp.tabaco) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">FRECUENCIA: </span> ' + _v(anp.tabacoDesc) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">SUSTANCIAS: </span> ' + _sino(anp.sustancias) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">FRECUENCIA: </span> ' + _v(anp.sustanciasDesc) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">DEPORTE: </span> ' + _sino(anp.deporte) + '</div>' +
      '<div style="width:30%;"><span class="doc-label">CUAL: </span> ' + _v(anp.deporteDesc) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:16%;"><span class="doc-label">TETANOS: </span> ' + _sino(vac.tetanos) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">INFLUENZA: </span> ' + _sino(vac.influenza) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">HEPATITIS B: </span> ' + _sino(vac.hepatitisB) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">COVID: </span> ' + _sino(vac.covid) + '</div>' +
      '<div style="width:18%;"><span class="doc-label">DESPARASITACION: </span> ' + _sino(anp.desparasitacion) + '</div>' +
      '<div style="width:18%;"><span class="doc-label">ALERGIAS: </span> ' + _v(anp.alergias) + '</div>' +
    '</div>';

  // 4. ANTECEDENTES HEREDO-FAMILIARES
  var hfEnfermedades = ['Hipertension','Enf. Cardiovasculares','Diabetes Mellitus','Enf. Renales','Osteoartritis','Cancer','Enf. Psiquiatricas','Otros'];
  var hfFamiliares = ['Madre','Padre','Hermanos','Abuelos','Hijos','Complicaciones'];
  var filasHF = '';
  hfEnfermedades.forEach(function(enf, i) {
    var key = 'enf_' + i;
    var vals = (hf.tabla && hf.tabla[key]) || {};
    var celdas = '';
    hfFamiliares.forEach(function(fam, j) {
      var fkey = 'fam_' + j;
      var w = j === 5 ? '18%' : '10%';
      celdas += '<div style="width:' + w + ';">' + (j === 5 ? _v(vals[fkey]) : _sino(vals[fkey])) + '</div>';
    });
    filasHF += '<div class="doc-row"><div style="width:22%;text-align:left;padding-left:6px;">' + enf + '</div>' + celdas + '</div>';
  });

  pagina1 +=
    '<div class="doc-section-title">4. ANTECEDENTES HEREDO-FAMILIARES</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:22%;">Enfermedad</div>' +
      '<div style="width:10%;">Madre</div>' +
      '<div style="width:10%;">Padre</div>' +
      '<div style="width:10%;">Hermanos</div>' +
      '<div style="width:10%;">Abuelos</div>' +
      '<div style="width:10%;">Hijos</div>' +
      '<div style="width:18%;">Complicaciones</div>' +
    '</div>' +
    filasHF;

  // ========== PAGINA 2 ==========
  // 5. ANTECEDENTES PATOLOGICOS
  var apItems = ['Hospitalizaciones','Interv. quirurgicas','Transfusiones','Fracturas','Esguinces','Luxaciones'];
  var filasApItems = '';
  apItems.forEach(function(item, i) {
    var key = 'item_' + i;
    var val = (ap.items && ap.items[key]) || {};
    filasApItems +=
      '<div class="doc-row">' +
        '<div style="width:30%;text-align:left;padding-left:6px;">' + item + '</div>' +
        '<div style="width:15%;">' + _sino(val.tiene) + '</div>' +
        '<div style="width:55%;">' + _v(val.motivo) + '</div>' +
      '</div>';
  });

  var enfIzqNombres = ['Hipertension Arterial','Diabetes Mellitus','Enf. Cardiovasculares','Enf. Renales','Enf. Gastrointestinales','Artritis','Osteoartritis','Cancer'];
  var enfDerNombres = ['EPOC','Asma','Neumonias','Fibromialgias','Enf. Autoinmunes','Enf. Psiquiatricas','Convulsiones/Epilepsias','Otros'];

  var filasEnfIzq = '';
  enfIzqNombres.forEach(function(enf, i) {
    var key = 'enf_' + i;
    var val = (ap.enfermedades && ap.enfermedades[key]) || {};
    filasEnfIzq +=
      '<div class="doc-row">' +
        '<div style="width:40%;text-align:left;padding-left:6px;">' + enf + '</div>' +
        '<div style="width:15%;">' + _sino(val.tiene) + '</div>' +
        '<div style="width:45%;">' + _v(val.tratamiento) + '</div>' +
      '</div>';
  });

  var filasEnfDer = '';
  enfDerNombres.forEach(function(enf, i) {
    var key = 'enf_' + (i + 8);
    var val = (ap.enfermedades && ap.enfermedades[key]) || {};
    filasEnfDer +=
      '<div class="doc-row">' +
        '<div style="width:40%;text-align:left;padding-left:6px;">' + enf + '</div>' +
        '<div style="width:15%;">' + _sino(val.tiene) + '</div>' +
        '<div style="width:45%;">' + _v(val.tratamiento) + '</div>' +
      '</div>';
  });

  var pagina2 =
    buildCabeceraDocumento(2, 2) +

    '<div class="doc-section-title">5. ANTECEDENTES PATOLOGICOS</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:30%;"></div>' +
      '<div style="width:15%;">Si/No</div>' +
      '<div style="width:55%;">Motivo / Edad / Observacion</div>' +
    '</div>' +
    filasApItems +

    '<div class="doc-row-header" style="margin-top:0.3rem;">' +
      '<div style="width:50%;">Enfermedad / Sistema</div>' +
      '<div style="width:50%;">Enfermedad / Sistema</div>' +
    '</div>' +
    '<div style="display:flex;width:100%;">' +
      '<div style="width:50%;">' +
        '<div class="doc-row-header"><div style="width:40%;">Enfermedad</div><div style="width:15%;">Si/No</div><div style="width:45%;">Tratamiento</div></div>' +
        filasEnfIzq +
      '</div>' +
      '<div style="width:50%;">' +
        '<div class="doc-row-header"><div style="width:40%;">Enfermedad</div><div style="width:15%;">Si/No</div><div style="width:45%;">Tratamiento</div></div>' +
        filasEnfDer +
      '</div>' +
    '</div>';

  // 6. SIGNOS VITALES + SOMATOMETRIA
  pagina2 +=
    '<div class="doc-section-title">6. SIGNOS VITALES Y SOMATOMETRIA</div>' +
    '<div class="doc-row">' +
      '<div style="width:16%;"><span class="doc-label">T/A: </span> ' + _v(sv.ta) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">FC: </span> ' + _v(sv.fc) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">FR: </span> ' + _v(sv.fr) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">TEMP: </span> ' + _v(sv.temp) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">SpO2: </span> ' + _v(sv.spo2) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">PESO: </span> ' + _v(sm.peso) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">TALLA: </span> ' + _v(sm.talla) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">IMC: </span> ' + _v(sm.imc) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CINTURA: </span> ' + _v(sm.cintura) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CADERA: </span> ' + _v(sm.cadera) + '</div>' +
      '<div style="width:40%;">&nbsp;</div>' +
    '</div>';

  // 7. EXPLORACION FISICA (Parte 1 - Cabeza, Torax, Abdomen, Extremidades, Columna)
  var cc = ef.cabezaCuello || {};
  var ojos = cc.ojos || {};
  var oidos = cc.oidos || {};
  var nariz = cc.nariz || {};
  var boca = cc.boca || {};
  var tor = ef.torax || {};
  var abd = ef.abdomen || {};
  var ext = ef.extremidades || {};
  var sup = ext.superiores || {};
  var inf = ext.inferiores || {};
  var col = ef.columna || {};

  pagina2 +=
    '<div class="doc-section-title">7. EXPLORACION FISICA</div>' +

    // Ojos
    '<div class="doc-row-header"><div style="width:30%;">OJOS</div><div style="width:35%;">Ojo Derecho</div><div style="width:35%;">Ojo Izquierdo</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Agudeza visual</div><div style="width:35%;">' + _v(ojos.agudezaDer) + '</div><div style="width:35%;">' + _v(ojos.agudezaIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Ident. de color</div><div style="width:35%;">' + _v(ojos.colorDer) + '</div><div style="width:35%;">' + _v(ojos.colorIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Mov. oculares</div><div style="width:35%;">' + _v(ojos.movimientosDer) + '</div><div style="width:35%;">' + _v(ojos.movimientosIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Campo visual</div><div style="width:35%;">' + _v(ojos.campoVisualDer) + '</div><div style="width:35%;">' + _v(ojos.campoVisualIzq) + '</div></div>' +

    // Oidos
    '<div class="doc-row-header" style="margin-top:0.2rem;"><div style="width:30%;">OIDOS</div><div style="width:35%;">Oido Derecho</div><div style="width:35%;">Oido Izquierdo</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Conducto interno</div><div style="width:35%;">' + _v(oidos.conductoInternoDer) + '</div><div style="width:35%;">' + _v(oidos.conductoInternoIzq) + '</div></div>' +
    '<div class="doc-row"><div style="width:30%;text-align:left;padding-left:6px;">Conducto externo</div><div style="width:35%;">' + _v(oidos.conductoExternoDer) + '</div><div style="width:35%;">' + _v(oidos.conductoExternoIzq) + '</div></div>' +

    // Nariz, Faringe, Tiroides, Boca
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:25%;"><span class="doc-label">NARIZ CONF.: </span> ' + _v(nariz.conformacion) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">TABIQUE: </span> ' + _v(nariz.tabique) + '</div>' +
      '<div style="width:50%;"><span class="doc-label">FARINGE/AMIGDALAS: </span> ' + _v(cc.faringeAmigdalas) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">TIROIDES: </span> ' + _v(cc.tiroides) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">ADENOPATIAS: </span> ' + _sino(cc.adenopatias) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DOLOROSAS: </span> ' + _sino(cc.dolorosas) + '</div>' +
      '<div style="width:25%;">&nbsp;</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">DENTADURA: </span> ' + _v(boca.dentadura) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">FALTANTES: </span> ' + _v(boca.faltantes) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">TRATAM.: </span> ' + _v(boca.tratamientos) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">CARIES: </span> ' + _v(boca.caries) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">LENGUA: </span> ' + _v(boca.lengua) + '</div>' +
    '</div>' +

    // Torax
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">TORAX</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">CONFORM.: </span> ' + _v(tor.conformacion) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">AMPLEXION: </span> ' + _v(tor.amplexion) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">AMPLEXACION: </span> ' + _v(tor.amplexacion) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">MURM. VES.: </span> ' + _v(tor.murmulloVesicular) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">ESTERTORES: </span> ' + _v(tor.estertores) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">R. CARDIACOS: </span> ' + _v(tor.ruidosCardiacos) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:100%;"><span class="doc-label">PULSOS: </span> ' + _v(tor.pulsos) + '</div>' +
    '</div>' +

    // Abdomen
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">ABDOMEN</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:20%;"><span class="doc-label">CONFORM.: </span> ' + _v(abd.conformacion) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">PARED: </span> ' + _v(abd.pared) + '</div>' +
      '<div style="width:20%;"><span class="doc-label">PERISTALSIS: </span> ' + _v(abd.peristalsis) + '</div>' +
      '<div style="width:14%;"><span class="doc-label">HIGADO: </span> ' + _sino(abd.higado) + '</div>' +
      '<div style="width:13%;"><span class="doc-label">BAZO: </span> ' + _sino(abd.bazo) + '</div>' +
      '<div style="width:13%;"><span class="doc-label">MASAS TUM.: </span> ' + _sino(abd.masasTumorales) + '</div>' +
    '</div>' +

    // Extremidades
    '<div class="doc-row-header" style="margin-top:0.2rem;">' +
      '<div style="width:18%;">EXTREMIDADES</div>' +
      '<div style="width:16%;">Simetria</div>' +
      '<div style="width:16%;">Movilidad</div>' +
      '<div style="width:16%;">Fuerza</div>' +
      '<div style="width:18%;">Articulaciones</div>' +
      '<div style="width:16%;">Varices</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:18%;font-weight:600;">Superiores</div>' +
      '<div style="width:16%;">' + _v(sup.simetria) + '</div>' +
      '<div style="width:16%;">' + _v(sup.movilidad) + '</div>' +
      '<div style="width:16%;">' + _v(sup.fuerza) + '</div>' +
      '<div style="width:18%;">' + _v(sup.articulaciones) + '</div>' +
      '<div style="width:16%;">' + _v(sup.varices) + '</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:18%;font-weight:600;">Inferiores</div>' +
      '<div style="width:16%;">' + _v(inf.simetria) + '</div>' +
      '<div style="width:16%;">' + _v(inf.movilidad) + '</div>' +
      '<div style="width:16%;">' + _v(inf.fuerza) + '</div>' +
      '<div style="width:18%;">' + _v(inf.articulaciones) + '</div>' +
      '<div style="width:16%;">' + _v(inf.varices) + '</div>' +
    '</div>' +

    // Columna Vertebral
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">COLUMNA VERTEBRAL</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:18%;"><span class="doc-label">MARCAS CONG.: </span> ' + _v(col.marcasCongenitas) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">LORDOSIS: </span> ' + _v(col.lordosis) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">ESCOLIOSIS: </span> ' + _v(col.escoliosis) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">MOVILIDAD: </span> ' + _v(col.movilidad) + '</div>' +
      '<div style="width:18%;"><span class="doc-label">P. DOLOROSOS: </span> ' + _v(col.puntosDolorosos) + '</div>' +
      '<div style="width:16%;"><span class="doc-label">MARCHA P-T: </span> ' + _v(col.marchaPuntaTalon) + '</div>' +
    '</div>';

  // ========== CONTINUACION PAGINA 2 ==========
  var piel = ef.pielAnexos || {};
  var sn = ef.sistemaNervioso || {};
  var gu = ef.genitourinarios || {};
  var go = ef.ginecoObstetricos || {};

  pagina2 +=

    // Piel y Anexos
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">PIEL Y ANEXOS</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">DERMATOSIS: </span> ' + _v(piel.dermatosis) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">DERMATITIS: </span> ' + _v(piel.dermatitis) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">PSORIASIS: </span> ' + _v(piel.psoriasis) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">INF. CUTANEA: </span> ' + _v(piel.infeccionCutanea) + '</div>' +
    '</div>' +

    // Sistema Nervioso
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">SISTEMA NERVIOSO</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">CONDUCTA: </span> ' + _v(sn.conducta) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">LENGUAJE: </span> ' + _v(sn.lenguaje) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">MOTRICIDAD: </span> ' + _v(sn.motricidad) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">OTROS: </span> ' + _v(sn.otros) + '</div>' +
    '</div>' +

    // Genitourinarios
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
      '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">GENITOURINARIOS</div>' +
    '</div>' +
    '<div class="doc-row">' +
      '<div style="width:25%;"><span class="doc-label">TRAST. INFLAM.: </span> ' + _v(gu.trastornoInflamatorio) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">INFECCIONES: </span> ' + _v(gu.infecciones) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">COND. NEOPL.: </span> ' + _v(gu.condicionesNeoplasicas) + '</div>' +
      '<div style="width:25%;"><span class="doc-label">OBSTRUCCIONES: </span> ' + _v(gu.obstrucciones) + '</div>' +
    '</div>';

  // Gineco-Obstetricos (solo si sexo es F)
  if (dp.sexo === 'F') {
    pagina2 +=
      '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.2rem;">' +
        '<div style="width:100%;font-weight:700;background:hsl(189,5.3%,93.7%);">GINECO-OBSTETRICOS</div>' +
      '</div>' +
      '<div class="doc-row">' +
        '<div style="width:20%;"><span class="doc-label">FUM: </span> ' + _v(formatFecha(go.fum)) + '</div>' +
        '<div style="width:20%;"><span class="doc-label">MENARCA: </span> ' + _v(go.menarca) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">GESTACIONES: </span> ' + _v(go.gestaciones) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">DISTOCICO: </span> ' + _v(go.distocico) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">EUTOCICO: </span> ' + _v(go.eutocico) + '</div>' +
        '<div style="width:15%;"><span class="doc-label">ABORTO: </span> ' + _v(go.aborto) + '</div>' +
      '</div>' +
      '<div class="doc-row">' +
        '<div style="width:25%;"><span class="doc-label">FUP: </span> ' + _v(formatFecha(go.fup)) + '</div>' +
        '<div style="width:25%;"><span class="doc-label">MENOPAUSIA: </span> ' + _v(go.menopausia) + '</div>' +
        '<div style="width:25%;"><span class="doc-label">METODO P.F.: </span> ' + _v(go.metodoPlanificacion) + '</div>' +
        '<div style="width:25%;"><span class="doc-label">ENFERMEDADES: </span> ' + _v(go.enfermedades) + '</div>' +
      '</div>';
  }

  // 8. EXAMENES COMPLEMENTARIOS
  var examenesComp = [
    { key: 'biometriaHematica', label: 'Biometria Hematica' },
    { key: 'orina', label: 'Examen de Orina' },
    { key: 'quimicaSanguinea', label: 'Quimica Sanguinea' },
    { key: 'antidoping', label: 'Antidoping' },
    { key: 'espirometria', label: 'Espirometria' },
    { key: 'audiometria', label: 'Audiometria' },
    { key: 'coprocultivo', label: 'Coprocultivo' }
  ];

  var filasExComp = '';
  examenesComp.forEach(function(ex) {
    var val = ec[ex.key] || {};
    filasExComp +=
      '<div class="doc-row">' +
        '<div style="width:30%;text-align:left;padding-left:6px;">' + ex.label + '</div>' +
        '<div style="width:15%;">' + _sino(val.aplica) + '</div>' +
        '<div style="width:55%;">' + _v(val.resultados) + '</div>' +
      '</div>';
  });

  pagina2 +=
    '<div class="doc-section-title">8. EXAMENES COMPLEMENTARIOS</div>' +
    '<div class="doc-row-header">' +
      '<div style="width:30%;">Examen</div>' +
      '<div style="width:15%;">Aplica</div>' +
      '<div style="width:55%;">Resultados</div>' +
    '</div>' +
    filasExComp;

  // Firma del candidato
  pagina2 +=
    '<div class="doc-firma-line">FIRMA DEL CANDIDATO</div>';

  // RESULTADOS
  pagina2 +=
    '<div class="doc-section-title">RESULTADOS</div>' +
    '<div class="doc-row">' +
      '<div style="width:50%;"><span class="doc-label">FECHA RESULTADO: </span> ' + _v(formatFecha(examen.fechaResultado)) + '</div>' +
      '<div style="width:50%;"><span class="doc-label">PUESTO: </span> ' + _v(puestoCandidato) + '</div>' +
    '</div>';

  // Analisis
  pagina2 +=
    '<div class="doc-row">' +
      '<div style="width:100%;min-height:2rem;text-align:left;padding-left:6px;"><span class="doc-label">ANALISIS GENERAL: </span> ' + _v(res.analisis) + '</div>' +
    '</div>';

  // Diagnosticos
  var diagnosticos = res.diagnostico || [];
  for (var di = 0; di < 5; di++) {
    pagina2 +=
      '<div class="doc-row">' +
        '<div style="width:100%;text-align:left;padding-left:6px;"><span class="doc-label">DIAGNOSTICO ' + (di + 1) + ': </span> ' + _v(diagnosticos[di]) + '</div>' +
      '</div>';
  }

  // Recomendaciones
  var recomendaciones = res.recomendaciones || [];
  for (var ri = 0; ri < 3; ri++) {
    pagina2 +=
      '<div class="doc-row">' +
        '<div style="width:100%;text-align:left;padding-left:6px;"><span class="doc-label">RECOMENDACION ' + (ri + 1) + ': </span> ' + _v(recomendaciones[ri]) + '</div>' +
      '</div>';
  }

  // Resultado APTO / NO APTO
  var resultadoBadgeClass = examen.resultado === 'apto' ? 'apto' : 'no-apto';
  var resultadoTexto = examen.resultado === 'apto' ? 'APTO' : 'NO APTO';

  pagina2 +=
    '<div style="display:flex;align-items:center;justify-content:center;margin-top:0.5rem;gap:1rem;">' +
      '<span class="doc-label" style="font-size:12px;">RESULTADO:</span>' +
      '<span class="doc-resultado-badge ' + resultadoBadgeClass + '">' + resultadoTexto + '</span>' +
    '</div>' +
    '<div class="doc-firma-line">SALUD OCUPACIONAL</div>' +
    '<div style="text-align:center;font-size:9px;color:hsl(189,5.3%,52.7%);margin-top:0.2rem;">' + _v(examen.realizadoPor) + '</div>';

  // Comentario concluyente
  pagina2 +=
    '<div class="doc-row" style="border-top:1px solid hsl(189,5.3%,52.7%);margin-top:0.3rem;">' +
      '<div style="width:100%;min-height:2.5rem;text-align:left;padding-left:6px;"><span class="doc-label">COMENTARIO CONCLUYENTE: </span> ' + _v(examen.comentarioFinal) + '</div>' +
    '</div>';

  // Ensamblar las 3 paginas
  var html =
    '<div class="pdf-container--print">' +
      '<div class="page__container">' + pagina1 + '</div>' +
      '<div class="page-break"></div>' +
      '<div class="page__container">' + pagina2 + '</div>' +
    '</div>';

  document.getElementById('detalle-examen-content').innerHTML = html;
  showView('detalle-examen');
}

// ==================== LISTENER SEXO (mostrar/ocultar gineco) ====================
function setupSexoListener() {
  document.addEventListener('change', function(e) {
    if (e.target && e.target.getAttribute('data-campo') === 'sexo' && e.target.getAttribute('data-seccion') === 'datosPersonales') {
      var ginecoSection = document.getElementById('ef-gineco-section');
      if (ginecoSection) ginecoSection.style.display = e.target.value === 'F' ? '' : 'none';
    }
  });
}

// ==================== DATOS DE PRUEBA ====================
function insertarDatosPrueba() {
  // Solo insertar si no hay examenes completados
  var existentes = JSON.parse(localStorage.getItem('examenesMedicos') || '[]');
  if (existentes.some(function(e) { return e.resultado; })) return;

  var vacantesDemo = JSON.parse(localStorage.getItem('vacantes') || '[]');
  if (!vacantesDemo.length) {
    vacantesDemo = [
      { id: 9001, titulo: 'Operador de Produccion', departamento: 'Manufactura' },
      { id: 9002, titulo: 'Auxiliar de Almacen', departamento: 'Logistica' },
      { id: 9003, titulo: 'Tecnico de Mantenimiento', departamento: 'Mantenimiento' },
      { id: 9004, titulo: 'Analista de Calidad', departamento: 'Calidad' },
      { id: 9005, titulo: 'Auxiliar Administrativo', departamento: 'Administracion' }
    ];
    localStorage.setItem('vacantes', JSON.stringify(vacantesDemo));
  }

  var candidatosDemo = JSON.parse(localStorage.getItem('candidatos') || '[]');
  var nombres = [
    { id: 8001, nombre: 'Juan Carlos', apellidos: 'Lopez Martinez', email: 'jclopez@test.com', telefono: '4771234567', fechaNacimiento: '1990-05-15', escolaridad: 'Preparatoria', vacanteId: 9001, etapa: 'entrevista-jefe', fechaAplicacion: '2026-02-10' },
    { id: 8002, nombre: 'Maria Fernanda', apellidos: 'Garcia Hernandez', email: 'mfgarcia@test.com', telefono: '4779876543', fechaNacimiento: '1995-08-22', escolaridad: 'Licenciatura', vacanteId: 9002, etapa: 'entrevista-jefe', fechaAplicacion: '2026-02-12' },
    { id: 8003, nombre: 'Roberto', apellidos: 'Sanchez Perez', email: 'rsanchez@test.com', telefono: '4775551234', fechaNacimiento: '1988-12-03', escolaridad: 'Ingenieria', vacanteId: 9003, etapa: 'rechazado', etapaRechazo: 'revision-medica', fechaAplicacion: '2026-02-14' },
    { id: 8004, nombre: 'Ana Lucia', apellidos: 'Torres Ramirez', email: 'altorres@test.com', telefono: '4773214567', fechaNacimiento: '1992-03-28', escolaridad: 'Licenciatura', vacanteId: 9004, etapa: 'entrevista-jefe', fechaAplicacion: '2026-02-18' },
    { id: 8005, nombre: 'Pedro', apellidos: 'Morales Jimenez', email: 'pmorales@test.com', telefono: '4776789012', fechaNacimiento: '1985-07-10', escolaridad: 'Tecnico', vacanteId: 9005, etapa: 'entrevista-jefe', fechaAplicacion: '2026-02-20' }
  ];

  nombres.forEach(function(c) {
    if (!candidatosDemo.some(function(x) { return x.id === c.id; })) {
      candidatosDemo.push(c);
    }
  });
  localStorage.setItem('candidatos', JSON.stringify(candidatosDemo));

  var examenesDemo = [
    {
      id: 7001, candidatoId: 8001, fechaAplicacion: '2026-02-10', resultado: 'apto',
      fechaResultado: '2026-02-11', realizadoPor: 'Gabriela Elizabeth Velazquez Cardenas',
      comentarioFinal: 'Candidato en buenas condiciones generales de salud. Sin hallazgos patologicos relevantes. Apto para actividades operativas.',
      datos: {
        datosPersonales: { nombre: 'Juan Carlos Lopez Martinez', puestoCandidato: 'Operador de Produccion', edad: '35', sexo: 'M', estadoCivil: 'casado', fechaNacimiento: '1990-05-15', lugarNacimiento: 'Leon, Gto.', escolaridad: 'Preparatoria', domicilio: 'Calle Reforma 123, Col. Centro', telefono: '4771234567', telefonoEmergencia: '4779991234', contactoEmergencia: 'Maria Martinez (Madre)', grupoRH: 'O+', nss: '12345678901', clinicaAdscripcion: 'UMF 53' },
        antecedentesLaborales: { agentes: { agente_0: { expuesto: 'no' }, agente_7: { expuesto: 'si', duracion: '3 anios', epp: 'Tapones auditivos', capacitacion: 'Si', empresa: 'Fabrica ABC' } }, riesgoTrabajo: { tipo: '', calificacion: '', lesion: '', diasOtorgados: '' }, enfermedadLaboral: { calificacion: '', lesionPatologia: '', diasOtorgados: '' } },
        antecedentesNoPatologicos: { alcohol: 'si', alcoholDesc: 'Ocasional, fines de semana', tabaco: 'no', tabacoDesc: '', sustancias: 'no', sustanciasDesc: '', deporte: 'si', deporteDesc: 'Futbol 2 veces por semana', desparasitacion: 'si', desparasitacionFecha: '2025-06-01', alergias: 'Ninguna', vacunas: { tetanos: 'si', influenza: 'si', hepatitisB: 'si', covid: 'si' } },
        heredoFamiliares: { tabla: { enf_0: { fam_0: 'si', fam_1: 'no', fam_2: 'no', fam_3: 'si', fam_4: 'no', fam_5: 'Abuelo paterno' }, enf_2: { fam_0: 'no', fam_1: 'si', fam_2: 'no', fam_3: 'no', fam_4: 'no', fam_5: 'Padre en tratamiento' } } },
        antecedentesPatologicos: { items: { item_0: { tiene: 'no', motivo: '' }, item_3: { tiene: 'si', motivo: 'Fractura de muneca a los 12 anios' } }, enfermedades: { enf_0: { tiene: 'no', tratamiento: '' }, enf_1: { tiene: 'no', tratamiento: '' } } },
        signosVitales: { ta: '120/80', fc: '72', fr: '18', temp: '36.5', spo2: '98' },
        somatometria: { peso: '78', talla: '175', imc: '25.5', cintura: '88', cadera: '96' },
        exploracionFisica: {
          cabezaCuello: { ojos: { agudezaDer: '20/20', agudezaIzq: '20/20', colorDer: 'Normal', colorIzq: 'Normal', movimientosDer: 'Normal', movimientosIzq: 'Normal', campoVisualDer: 'Completo', campoVisualIzq: 'Completo' }, oidos: { conductoInternoDer: 'Normal', conductoInternoIzq: 'Normal', conductoExternoDer: 'Permeable', conductoExternoIzq: 'Permeable' }, nariz: { conformacion: 'Normal', tabique: 'Central' }, boca: { dentadura: 'Completa', faltantes: '0', tratamientos: 'Ninguno', caries: '2', lengua: 'Normal' }, faringeAmigdalas: 'Sin hipertrofia', tiroides: 'normal', adenopatias: 'no', dolorosas: 'no' },
          torax: { conformacion: 'normolineo', amplexion: 'normal', amplexacion: 'normal', murmulloVesicular: 'normal', estertores: '', ruidosCardiacos: 'normal', pulsos: 'Simetricos, normales' },
          abdomen: { conformacion: 'plano', pared: 'Normal', peristalsis: 'normal', higado: 'no', bazo: 'no', masasTumorales: 'no' },
          extremidades: { superiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Sin alteraciones', varices: 'No' }, inferiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Sin alteraciones', varices: 'No' } },
          columna: { marcasCongenitas: 'No', lordosis: 'Normal', escoliosis: 'No', movilidad: 'Completa', puntosDolorosos: 'No', marchaPuntaTalon: 'Normal' },
          pielAnexos: { dermatosis: 'No', dermatitis: 'No', psoriasis: 'No', infeccionCutanea: 'No' },
          sistemaNervioso: { conducta: 'Adecuada', lenguaje: 'Coherente', motricidad: 'Normal', otros: '' },
          genitourinarios: { trastornoInflamatorio: 'No', infecciones: 'No', condicionesNeoplasicas: 'No', obstrucciones: 'No' }
        },
        examenesComplementarios: { biometriaHematica: { aplica: 'si', resultados: 'Dentro de parametros normales' }, orina: { aplica: 'si', resultados: 'Normal' }, quimicaSanguinea: { aplica: 'si', resultados: 'Glucosa 95 mg/dL' }, antidoping: { aplica: 'si', resultados: 'negativo' }, espirometria: { aplica: 'no', resultados: '' }, audiometria: { aplica: 'si', resultados: 'Normal bilateral' }, coprocultivo: { aplica: 'no', resultados: '' } },
        resultados: { analisis: 'Paciente masculino de 35 anios sin antecedentes patologicos de importancia. Exploracion fisica sin hallazgos relevantes.', diagnostico: ['Clinicamente sano', 'Sobrepeso grado I', '', '', ''], recomendaciones: ['Control de peso con dieta y ejercicio', 'Revision dental por caries', ''] }
      }
    },
    {
      id: 7002, candidatoId: 8002, fechaAplicacion: '2026-02-12', resultado: 'apto',
      fechaResultado: '2026-02-13', realizadoPor: 'Nayeli Guadalupe Sanchez Landin',
      comentarioFinal: 'Candidata sana, sin restricciones para laborar en el puesto solicitado.',
      datos: {
        datosPersonales: { nombre: 'Maria Fernanda Garcia Hernandez', puestoCandidato: 'Auxiliar de Almacen', edad: '30', sexo: 'F', estadoCivil: 'soltero', fechaNacimiento: '1995-08-22', lugarNacimiento: 'Irapuato, Gto.', escolaridad: 'Licenciatura', domicilio: 'Av. Americas 456, Col. Jardines', telefono: '4779876543', telefonoEmergencia: '4778885566', contactoEmergencia: 'Pedro Garcia (Padre)', grupoRH: 'A+', nss: '98765432101', clinicaAdscripcion: 'UMF 47' },
        antecedentesLaborales: { agentes: {}, riesgoTrabajo: {}, enfermedadLaboral: {} },
        antecedentesNoPatologicos: { alcohol: 'no', alcoholDesc: '', tabaco: 'no', tabacoDesc: '', sustancias: 'no', sustanciasDesc: '', deporte: 'si', deporteDesc: 'Yoga 3 veces por semana', desparasitacion: 'si', desparasitacionFecha: '2025-09-15', alergias: 'Penicilina', vacunas: { tetanos: 'si', influenza: 'si', hepatitisB: 'si', covid: 'si' } },
        heredoFamiliares: { tabla: { enf_2: { fam_0: 'si', fam_1: 'no', fam_2: 'no', fam_3: 'no', fam_4: 'no', fam_5: 'Madre con DM2' } } },
        antecedentesPatologicos: { items: {}, enfermedades: {} },
        signosVitales: { ta: '110/70', fc: '68', fr: '16', temp: '36.3', spo2: '99' },
        somatometria: { peso: '58', talla: '162', imc: '22.1', cintura: '72', cadera: '90' },
        exploracionFisica: {
          cabezaCuello: { ojos: { agudezaDer: '20/20', agudezaIzq: '20/25', colorDer: 'Normal', colorIzq: 'Normal', movimientosDer: 'Normal', movimientosIzq: 'Normal', campoVisualDer: 'Completo', campoVisualIzq: 'Completo' }, oidos: { conductoInternoDer: 'Normal', conductoInternoIzq: 'Normal', conductoExternoDer: 'Permeable', conductoExternoIzq: 'Permeable' }, nariz: { conformacion: 'Normal', tabique: 'Central' }, boca: { dentadura: 'Completa', faltantes: '0', tratamientos: 'Ortodoncia previa', caries: '0', lengua: 'Normal' }, faringeAmigdalas: 'Normal', tiroides: 'normal', adenopatias: 'no', dolorosas: 'no' },
          torax: { conformacion: 'normolineo', amplexion: 'normal', amplexacion: 'normal', murmulloVesicular: 'normal', ruidosCardiacos: 'normal', pulsos: 'Normales' },
          abdomen: { conformacion: 'plano', pared: 'Normal', peristalsis: 'normal', higado: 'no', bazo: 'no', masasTumorales: 'no' },
          extremidades: { superiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Normal', varices: 'No' }, inferiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Normal', varices: 'No' } },
          columna: { marcasCongenitas: 'No', lordosis: 'Normal', escoliosis: 'No', movilidad: 'Completa', puntosDolorosos: 'No', marchaPuntaTalon: 'Normal' },
          pielAnexos: { dermatosis: 'No', dermatitis: 'No', psoriasis: 'No', infeccionCutanea: 'No' },
          sistemaNervioso: { conducta: 'Adecuada', lenguaje: 'Coherente', motricidad: 'Normal', otros: '' },
          genitourinarios: { trastornoInflamatorio: 'No', infecciones: 'No', condicionesNeoplasicas: 'No', obstrucciones: 'No' },
          ginecoObstetricos: { fum: '2026-01-20', menarca: '12 anios', gestaciones: '0', distocico: '', eutocico: '', aborto: '0', fup: '', menopausia: '', metodoPlanificacion: 'DIU', enfermedades: 'Ninguna' }
        },
        examenesComplementarios: { biometriaHematica: { aplica: 'si', resultados: 'Normal' }, orina: { aplica: 'si', resultados: 'Normal' }, quimicaSanguinea: { aplica: 'si', resultados: 'Normal' }, antidoping: { aplica: 'si', resultados: 'negativo' }, espirometria: { aplica: 'no', resultados: '' }, audiometria: { aplica: 'no', resultados: '' }, coprocultivo: { aplica: 'no', resultados: '' } },
        resultados: { analisis: 'Paciente femenino de 30 anios clinicamente sana. Alergia a penicilina documentada.', diagnostico: ['Clinicamente sana', 'Alergia a penicilina', '', '', ''], recomendaciones: ['Portar identificacion de alergia a penicilina', '', ''] }
      }
    },
    {
      id: 7003, candidatoId: 8003, fechaAplicacion: '2026-02-14', resultado: 'no-apto',
      fechaResultado: '2026-02-15', realizadoPor: 'Gabriela Elizabeth Velazquez Cardenas',
      comentarioFinal: 'Candidato presenta hipertension arterial no controlada y obesidad grado II. No apto para actividades que requieran esfuerzo fisico intenso.',
      datos: {
        datosPersonales: { nombre: 'Roberto Sanchez Perez', puestoCandidato: 'Tecnico de Mantenimiento', edad: '37', sexo: 'M', estadoCivil: 'union-libre', fechaNacimiento: '1988-12-03', lugarNacimiento: 'Celaya, Gto.', escolaridad: 'Ingenieria', domicilio: 'Blvd. Torres Landa 789', telefono: '4775551234', telefonoEmergencia: '4772223344', contactoEmergencia: 'Laura Perez (Esposa)', grupoRH: 'B+', nss: '55667788990', clinicaAdscripcion: 'UMF 51' },
        antecedentesLaborales: { agentes: { agente_0: { expuesto: 'si', duracion: '5 anios', epp: 'Guantes', capacitacion: 'Si', empresa: 'Industrias XYZ' }, agente_7: { expuesto: 'si', duracion: '5 anios', epp: 'Tapones', capacitacion: 'Si', empresa: 'Industrias XYZ' } }, riesgoTrabajo: { tipo: 'imss', calificacion: 'si-trabajo', lesion: 'Lumbalgia', diasOtorgados: '15' }, enfermedadLaboral: {} },
        antecedentesNoPatologicos: { alcohol: 'si', alcoholDesc: 'Frecuente, 3-4 veces por semana', tabaco: 'si', tabacoDesc: '10 cigarrillos diarios, 15 anios', sustancias: 'no', sustanciasDesc: '', deporte: 'no', deporteDesc: '', desparasitacion: 'no', alergias: 'Ninguna', vacunas: { tetanos: 'si', influenza: 'no', hepatitisB: 'no', covid: 'si' } },
        heredoFamiliares: { tabla: { enf_0: { fam_0: 'si', fam_1: 'si', fam_2: 'no', fam_3: 'si', fam_4: 'no', fam_5: 'Ambos padres hipertensos' }, enf_1: { fam_0: 'si', fam_1: 'no', fam_2: 'no', fam_3: 'no', fam_4: 'no', fam_5: 'Madre con cardiopatia' }, enf_2: { fam_0: 'no', fam_1: 'si', fam_2: 'no', fam_3: 'si', fam_4: 'no', fam_5: 'Padre y abuelo paterno' } } },
        antecedentesPatologicos: { items: { item_0: { tiene: 'si', motivo: 'Apendicectomia a los 20 anios' } }, enfermedades: { enf_0: { tiene: 'si', tratamiento: 'Losartan 50mg, no controlado' } } },
        signosVitales: { ta: '155/95', fc: '88', fr: '20', temp: '36.7', spo2: '96' },
        somatometria: { peso: '105', talla: '170', imc: '36.3', cintura: '112', cadera: '108' },
        exploracionFisica: {
          cabezaCuello: { ojos: { agudezaDer: '20/30', agudezaIzq: '20/30', colorDer: 'Normal', colorIzq: 'Normal', movimientosDer: 'Normal', movimientosIzq: 'Normal', campoVisualDer: 'Completo', campoVisualIzq: 'Completo' }, oidos: { conductoInternoDer: 'Normal', conductoInternoIzq: 'Normal', conductoExternoDer: 'Permeable', conductoExternoIzq: 'Permeable' }, nariz: { conformacion: 'Normal', tabique: 'Desviado izq.' }, boca: { dentadura: 'Incompleta', faltantes: '3', tratamientos: 'Puente dental', caries: '5', lengua: 'Saburral' }, faringeAmigdalas: 'Hipertrofia leve', tiroides: 'normal', adenopatias: 'no', dolorosas: 'no' },
          torax: { conformacion: 'brevilineo', amplexion: 'normal', amplexacion: 'normal', murmulloVesicular: 'normal', estertores: 'finos', ruidosCardiacos: 'normal', pulsos: 'Taquicardicos' },
          abdomen: { conformacion: 'globoso', pared: 'Adiposa', peristalsis: 'normal', higado: 'no', bazo: 'no', masasTumorales: 'no' },
          extremidades: { superiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '4/5', articulaciones: 'Normal', varices: 'No' }, inferiores: { simetria: 'simetricas', movilidad: 'Limitada', fuerza: '4/5', articulaciones: 'Gonalgia bilateral', varices: 'Si, ambas piernas' } },
          columna: { marcasCongenitas: 'No', lordosis: 'Aumentada', escoliosis: 'No', movilidad: 'Limitada en flexion', puntosDolorosos: 'L4-L5', marchaPuntaTalon: 'Dificultad' },
          pielAnexos: { dermatosis: 'No', dermatitis: 'No', psoriasis: 'No', infeccionCutanea: 'No' },
          sistemaNervioso: { conducta: 'Adecuada', lenguaje: 'Coherente', motricidad: 'Normal', otros: '' },
          genitourinarios: { trastornoInflamatorio: 'No', infecciones: 'No', condicionesNeoplasicas: 'No', obstrucciones: 'No' }
        },
        examenesComplementarios: { biometriaHematica: { aplica: 'si', resultados: 'Leucocitosis leve' }, orina: { aplica: 'si', resultados: 'Proteinuria +' }, quimicaSanguinea: { aplica: 'si', resultados: 'Glucosa 128 mg/dL, Colesterol 260 mg/dL' }, antidoping: { aplica: 'si', resultados: 'negativo' }, espirometria: { aplica: 'si', resultados: 'Restriccion leve' }, audiometria: { aplica: 'si', resultados: 'Hipoacusia leve bilateral' }, coprocultivo: { aplica: 'no', resultados: '' } },
        resultados: { analisis: 'Paciente con multiples factores de riesgo cardiovascular. HTA no controlada, obesidad grado II, tabaquismo activo, dislipidemia.', diagnostico: ['Hipertension arterial no controlada', 'Obesidad grado II (IMC 36.3)', 'Dislipidemia', 'Tabaquismo activo', 'Lumbalgia cronica'], recomendaciones: ['Valoracion por cardiologia urgente', 'Programa de reduccion de peso', 'Dejar de fumar'] }
      }
    },
    {
      id: 7004, candidatoId: 8004, fechaAplicacion: '2026-02-18', resultado: 'apto',
      fechaResultado: '2026-02-19', realizadoPor: 'Dayana Areli Arellano Martinez',
      comentarioFinal: 'Candidata en buen estado de salud general. Miopia corregida con lentes. Apta sin restricciones.',
      datos: {
        datosPersonales: { nombre: 'Ana Lucia Torres Ramirez', puestoCandidato: 'Analista de Calidad', edad: '33', sexo: 'F', estadoCivil: 'casado', fechaNacimiento: '1992-03-28', lugarNacimiento: 'Salamanca, Gto.', escolaridad: 'Licenciatura', domicilio: 'Privada Los Olivos 12', telefono: '4773214567', telefonoEmergencia: '4771112233', contactoEmergencia: 'Carlos Torres (Esposo)', grupoRH: 'O-', nss: '11223344556', clinicaAdscripcion: 'UMF 53' },
        antecedentesLaborales: { agentes: {}, riesgoTrabajo: {}, enfermedadLaboral: {} },
        antecedentesNoPatologicos: { alcohol: 'si', alcoholDesc: 'Social, muy ocasional', tabaco: 'no', tabacoDesc: '', sustancias: 'no', sustanciasDesc: '', deporte: 'si', deporteDesc: 'Correr 4 veces por semana', desparasitacion: 'si', desparasitacionFecha: '2025-11-01', alergias: 'Polvo', vacunas: { tetanos: 'si', influenza: 'si', hepatitisB: 'si', covid: 'si' } },
        heredoFamiliares: { tabla: {} },
        antecedentesPatologicos: { items: {}, enfermedades: {} },
        signosVitales: { ta: '115/75', fc: '65', fr: '16', temp: '36.4', spo2: '99' },
        somatometria: { peso: '62', talla: '165', imc: '22.8', cintura: '74', cadera: '92' },
        exploracionFisica: {
          cabezaCuello: { ojos: { agudezaDer: '20/40 sc', agudezaIzq: '20/40 sc', colorDer: 'Normal', colorIzq: 'Normal', movimientosDer: 'Normal', movimientosIzq: 'Normal', campoVisualDer: 'Completo', campoVisualIzq: 'Completo' }, oidos: { conductoInternoDer: 'Normal', conductoInternoIzq: 'Normal', conductoExternoDer: 'Permeable', conductoExternoIzq: 'Permeable' }, nariz: { conformacion: 'Normal', tabique: 'Central' }, boca: { dentadura: 'Completa', faltantes: '0', tratamientos: 'Ninguno', caries: '1', lengua: 'Normal' }, faringeAmigdalas: 'Normal', tiroides: 'normal', adenopatias: 'no', dolorosas: 'no' },
          torax: { conformacion: 'normolineo', amplexion: 'normal', amplexacion: 'normal', murmulloVesicular: 'normal', ruidosCardiacos: 'normal', pulsos: 'Normales' },
          abdomen: { conformacion: 'plano', pared: 'Normal', peristalsis: 'normal', higado: 'no', bazo: 'no', masasTumorales: 'no' },
          extremidades: { superiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Normal', varices: 'No' }, inferiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Normal', varices: 'No' } },
          columna: { marcasCongenitas: 'No', lordosis: 'Normal', escoliosis: 'No', movilidad: 'Completa', puntosDolorosos: 'No', marchaPuntaTalon: 'Normal' },
          pielAnexos: { dermatosis: 'No', dermatitis: 'No', psoriasis: 'No', infeccionCutanea: 'No' },
          sistemaNervioso: { conducta: 'Adecuada', lenguaje: 'Coherente', motricidad: 'Normal', otros: '' },
          genitourinarios: { trastornoInflamatorio: 'No', infecciones: 'No', condicionesNeoplasicas: 'No', obstrucciones: 'No' },
          ginecoObstetricos: { fum: '2026-02-05', menarca: '13 anios', gestaciones: '2', distocico: '0', eutocico: '2', aborto: '0', fup: '2022-06-15', menopausia: '', metodoPlanificacion: 'Implante subdermico', enfermedades: 'Ninguna' }
        },
        examenesComplementarios: { biometriaHematica: { aplica: 'si', resultados: 'Normal' }, orina: { aplica: 'si', resultados: 'Normal' }, quimicaSanguinea: { aplica: 'si', resultados: 'Normal' }, antidoping: { aplica: 'si', resultados: 'negativo' }, espirometria: { aplica: 'no', resultados: '' }, audiometria: { aplica: 'no', resultados: '' }, coprocultivo: { aplica: 'no', resultados: '' } },
        resultados: { analisis: 'Paciente femenino sana. Miopia corregida con lentes. Sin otras alteraciones.', diagnostico: ['Miopia bilateral', 'Clinicamente sana', '', '', ''], recomendaciones: ['Uso de lentes correctivos', '', ''] }
      }
    },
    {
      id: 7005, candidatoId: 8005, fechaAplicacion: '2026-02-20', resultado: 'apto',
      fechaResultado: '2026-02-21', realizadoPor: 'Nayeli Guadalupe Sanchez Landin',
      comentarioFinal: 'Candidato apto. Prediabetes detectada, se recomienda seguimiento nutricional.',
      datos: {
        datosPersonales: { nombre: 'Pedro Morales Jimenez', puestoCandidato: 'Auxiliar Administrativo', edad: '40', sexo: 'M', estadoCivil: 'divorciado', fechaNacimiento: '1985-07-10', lugarNacimiento: 'Guanajuato, Gto.', escolaridad: 'Tecnico', domicilio: 'Calle Hidalgo 567, Col. San Juan', telefono: '4776789012', telefonoEmergencia: '4774445566', contactoEmergencia: 'Rosa Jimenez (Madre)', grupoRH: 'AB+', nss: '99887766554', clinicaAdscripcion: 'UMF 47' },
        antecedentesLaborales: { agentes: {}, riesgoTrabajo: {}, enfermedadLaboral: {} },
        antecedentesNoPatologicos: { alcohol: 'si', alcoholDesc: 'Social, 1-2 veces al mes', tabaco: 'no', tabacoDesc: 'Ex fumador, dejo hace 5 anios', sustancias: 'no', sustanciasDesc: '', deporte: 'si', deporteDesc: 'Caminata diaria 30 min', desparasitacion: 'si', desparasitacionFecha: '2025-08-20', alergias: 'Sulfas', vacunas: { tetanos: 'si', influenza: 'si', hepatitisB: 'no', covid: 'si' } },
        heredoFamiliares: { tabla: { enf_2: { fam_0: 'si', fam_1: 'si', fam_2: 'si', fam_3: 'si', fam_4: 'no', fam_5: 'Historia familiar fuerte de DM2' } } },
        antecedentesPatologicos: { items: {}, enfermedades: {} },
        signosVitales: { ta: '130/85', fc: '76', fr: '17', temp: '36.6', spo2: '97' },
        somatometria: { peso: '85', talla: '172', imc: '28.7', cintura: '95', cadera: '100' },
        exploracionFisica: {
          cabezaCuello: { ojos: { agudezaDer: '20/20', agudezaIzq: '20/25', colorDer: 'Normal', colorIzq: 'Normal', movimientosDer: 'Normal', movimientosIzq: 'Normal', campoVisualDer: 'Completo', campoVisualIzq: 'Completo' }, oidos: { conductoInternoDer: 'Normal', conductoInternoIzq: 'Normal', conductoExternoDer: 'Permeable', conductoExternoIzq: 'Permeable' }, nariz: { conformacion: 'Normal', tabique: 'Central' }, boca: { dentadura: 'Completa', faltantes: '1', tratamientos: 'Amalgamas', caries: '3', lengua: 'Normal' }, faringeAmigdalas: 'Normal', tiroides: 'normal', adenopatias: 'no', dolorosas: 'no' },
          torax: { conformacion: 'normolineo', amplexion: 'normal', amplexacion: 'normal', murmulloVesicular: 'normal', ruidosCardiacos: 'normal', pulsos: 'Normales' },
          abdomen: { conformacion: 'globoso', pared: 'Adiposa leve', peristalsis: 'normal', higado: 'no', bazo: 'no', masasTumorales: 'no' },
          extremidades: { superiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Normal', varices: 'No' }, inferiores: { simetria: 'simetricas', movilidad: 'Completa', fuerza: '5/5', articulaciones: 'Normal', varices: 'No' } },
          columna: { marcasCongenitas: 'No', lordosis: 'Normal', escoliosis: 'No', movilidad: 'Completa', puntosDolorosos: 'No', marchaPuntaTalon: 'Normal' },
          pielAnexos: { dermatosis: 'No', dermatitis: 'No', psoriasis: 'No', infeccionCutanea: 'No' },
          sistemaNervioso: { conducta: 'Adecuada', lenguaje: 'Coherente', motricidad: 'Normal', otros: '' },
          genitourinarios: { trastornoInflamatorio: 'No', infecciones: 'No', condicionesNeoplasicas: 'No', obstrucciones: 'No' }
        },
        examenesComplementarios: { biometriaHematica: { aplica: 'si', resultados: 'Normal' }, orina: { aplica: 'si', resultados: 'Glucosuria +' }, quimicaSanguinea: { aplica: 'si', resultados: 'Glucosa 118 mg/dL, HbA1c 6.2%' }, antidoping: { aplica: 'si', resultados: 'negativo' }, espirometria: { aplica: 'no', resultados: '' }, audiometria: { aplica: 'no', resultados: '' }, coprocultivo: { aplica: 'no', resultados: '' } },
        resultados: { analisis: 'Paciente masculino con sobrepeso y prediabetes. Antecedentes heredofamiliares fuertes de DM2. Requiere seguimiento.', diagnostico: ['Prediabetes (Glucosa 118, HbA1c 6.2%)', 'Sobrepeso (IMC 28.7)', 'Alergia a sulfas', '', ''], recomendaciones: ['Valoracion por nutriologia', 'Control glucemico trimestral', 'Portar identificacion de alergia a sulfas'] }
      }
    }
  ];

  existentes = existentes.concat(examenesDemo);
  localStorage.setItem('examenesMedicos', JSON.stringify(existentes));
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
  insertarDatosPrueba();
  sesionSalud = window.__SALUD_CONFIG || {};
  rolActual = sesionSalud.rol || 'salud';

  var buscar = document.getElementById('buscar-historial');
  var filtro = document.getElementById('filtro-resultado');
  if (buscar) buscar.addEventListener('input', renderHistorial);
  if (filtro) filtro.addEventListener('change', renderHistorial);

  setupSexoListener();
  renderPendientes();
  renderHistorial();
});
