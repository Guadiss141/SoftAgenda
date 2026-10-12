const TemaCache = (function () {
  const TEMA_CLARO = 'claro';
  const TEMA_OSCURO = 'oscuro';
  const PREFIJO_CLAVE = 'tema_usuario_';

  function obtenerClave() {
    const userId = (typeof USER_ID !== 'undefined' && USER_ID) ? USER_ID : 'invitado';
    return PREFIJO_CLAVE + userId;
  }

  function obtenerTemaGuardado() {
    const clave = obtenerClave();
    const tema = localStorage.getItem(clave);
    return (tema === TEMA_OSCURO) ? TEMA_OSCURO : TEMA_CLARO;
  }

  function guardarTema(tema) {
    const clave = obtenerClave();
    localStorage.setItem(clave, tema);
  }

  function aplicarTema(tema) {
    if (tema === TEMA_OSCURO) {
      document.documentElement.classList.add('tema-oscuro');
    } else {
      document.documentElement.classList.remove('tema-oscuro');
    }
  }

  function alternarTema() {
    const temaActual = obtenerTemaGuardado();
    const nuevoTema = (temaActual === TEMA_OSCURO) ? TEMA_CLARO : TEMA_OSCURO;
    guardarTema(nuevoTema);
    aplicarTema(nuevoTema);
    return nuevoTema;
  }

  function inicializar(switchId) {
    const temaGuardado = obtenerTemaGuardado();
    aplicarTema(temaGuardado);

    const switchEl = document.getElementById(switchId);
    if (!switchEl) return;

    // Sincroniza el estado visual del switch con el tema guardado
    switchEl.checked = (temaGuardado === TEMA_OSCURO);

    switchEl.addEventListener('change', function () {
      alternarTema();
    });
  }

return {
    inicializar,
    alternarTema,
    obtenerTemaGuardado,
    aplicarTema 
};
})();