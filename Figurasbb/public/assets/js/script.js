// Mostrar cuadro de inicio de sesión
function mostrarFormularioLogin() {
    document.getElementById('cuadro1').style.display = 'none';  // Oculta el cuadro de registro
    document.getElementById('cuadro-login').style.display = 'block';  // Muestra el cuadro de login
}

// Mostrar cuadro de registro (aunque ahora ya no está en uso)
function mostrarFormularioRegistro() {
    document.getElementById('cuadro-login').style.display = 'none';  // Oculta el cuadro de login
    document.getElementById('cuadro1').style.display = 'block';  // Muestra el cuadro de registro
}

// No es necesario en este caso ya que los formularios de registro fueron eliminados.
