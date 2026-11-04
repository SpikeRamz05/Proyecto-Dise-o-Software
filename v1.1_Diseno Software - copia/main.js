const { app, BrowserWindow } = require('electron');
const path = require('path');

function createWindow() {
    const mainWindow = new BrowserWindow({
        width: 1280,
        height: 800,
        webPreferences: {
            nodeIntegration: true,
            contextIsolation: false // <--- Esto es vital para que los scripts locales interactúen sin bloqueos de seguridad de Electron
        }
    });

    mainWindow.loadFile('index.html');
}

app.whenReady().then(() => {
    createWindow();

    app.on('activate', () => {
        if (BrowserWindow.getAllWindows().length === 0) {
            createWindow();
        }
    });
});

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit();
    }
});


/* =========================================
   ESTADO GLOBAL DE PERFILES
========================================= */
let listaPerfiles = [];
let perfilSeleccionadoIndex = null;

/* =========================================
   NAVEGACIÓN ENTRE PANTALLAS
========================================= */
function mostrarRegistro() {
    document.getElementById("pantalla-perfiles").style.display = "none";
    document.getElementById("pantalla-login-usuario").style.display = "none";
    document.getElementById("pantalla-registro").style.display = "block";
    
    document.getElementById("nombre-usuario").value = "";
    document.getElementById("contrasena-usuario").value = "";
    document.getElementById("alerta-registro").style.display = "none";
}

function mostrarPerfiles() {
    document.getElementById("pantalla-registro").style.display = "none";
    document.getElementById("pantalla-login-usuario").style.display = "none";
    document.getElementById("pantalla-perfiles").style.display = "block";
    
    actualizarVistaPerfiles();
}

/* =========================================
   SELECCIONAR PERFIL Y PREPARAR LOGIN
========================================= */
function seleccionarPerfil(index) {
    perfilSeleccionadoIndex = index;
    const perfil = listaPerfiles[index];

    document.getElementById("login-nombre-usuario").textContent = perfil.nombre;
    document.getElementById("login-avatar").textContent = perfil.nombre.charAt(0).toUpperCase();
    document.getElementById("contrasena-login").value = "";
    
    // Ocultar alerta al cambiar/ingresar a un perfil
    document.getElementById("alerta-login").style.display = "none";

    document.getElementById("pantalla-perfiles").style.display = "none";
    document.getElementById("pantalla-login-usuario").style.display = "block";
}

/* =========================================
   MOSTRAR / OCULTAR CONTRASEÑA
========================================= */
function alternarContrasena() {
    const campo = document.getElementById("contrasena-usuario");
    campo.type = campo.type === "password" ? "text" : "password";
}

function alternarContrasenaLogin() {
    const campo = document.getElementById("contrasena-login");
    campo.type = campo.type === "password" ? "text" : "password";
}

/* =========================================
   VALIDACIÓN Y CREACIÓN DE PERFIL
========================================= */
function validarRegistro() {
    const nombre = document.getElementById("nombre-usuario").value.trim();
    const contrasena = document.getElementById("contrasena-usuario").value.trim();
    const alerta = document.getElementById("alerta-registro");
    const textoAlerta = document.getElementById("texto-alerta");

    if (nombre === "" || contrasena === "") {
        textoAlerta.textContent = "Completa el nombre y la contraseña.";
        alerta.style.display = "flex";
        return;
    }

    if (contrasena.length < 4) {
        textoAlerta.textContent = "La contraseña debe tener al menos 4 caracteres.";
        alerta.style.display = "flex";
        return;
    }

    alerta.style.display = "none";
    listaPerfiles.push({ nombre: nombre, contrasena: contrasena });
    mostrarPerfiles();
}

/* =========================================
   RENDERIZAR PERFILES EN PANTALLA
========================================= */
function actualizarVistaPerfiles() {
    const contenedorPerfiles = document.getElementById("contenedor-perfiles");

    if (listaPerfiles.length === 0) {
        contenedorPerfiles.innerHTML = `
            <div class="sin-perfiles" id="mensaje-sin-perfiles">
                <svg class="icono-usuario" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a7 7 0 0 0-14 0v2"/>
                    <circle cx="13" cy="7" r="4"/>
                </svg>
                <h3>Aún no hay perfiles</h3>
                <p>Crea la primera cuenta para abrir la terminal.</p>
            </div>
        `;
        return;
    }

    let htmlTarjetas = "";
    listaPerfiles.forEach((perfil, index) => {
        const inicial = perfil.nombre.charAt(0).toUpperCase();
        htmlTarjetas += `
            <div class="tarjeta-perfil" onclick="seleccionarPerfil(${index})">
                <div class="perfil-info">
                    <div class="avatar-inicial">${inicial}</div>
                    <div class="detalles-usuario">
                        <h3>${perfil.nombre}</h3>
                        <p>Tocar para ingresar</p>
                    </div>
                </div>
                <svg class="flecha-perfil" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </div>
        `;
    });

    contenedorPerfiles.innerHTML = htmlTarjetas;
}

/* =========================================
   INICIO DE SESIÓN Y DASHBOARD
========================================= */
function iniciarSesion() {
    const pass = document.getElementById("contrasena-login").value.trim();
    const perfil = listaPerfiles[perfilSeleccionadoIndex];
    const alerta = document.getElementById("alerta-login");
    const textoAlerta = document.getElementById("texto-alerta-login");

    if (pass === "") {
        textoAlerta.textContent = "Ingresa tu contraseña para continuar.";
        alerta.style.display = "flex";
        return;
    }

    if (pass !== perfil.contrasena) {
        textoAlerta.textContent = "Contraseña incorrecta. Inténtalo de nuevo.";
        alerta.style.display = "flex";
        return;
    }

    alerta.style.display = "none";

    // Ocultar por completo la pantalla de login mediante una clase o display: none general
    document.querySelector(".pantalla-login").style.display = "none";

    // Cargar datos en el Dashboard
    document.getElementById("dashboard-usuario-nombre").textContent = perfil.nombre;
    document.getElementById("dashboard-avatar").textContent = perfil.nombre.charAt(0).toUpperCase();

    // Mostrar el Dashboard como bloque/flex
    document.getElementById("pantalla-dashboard").style.display = "flex";
    
    window.scrollTo(0, 0);
}

function cerrarSesion() {
    // Ocultar el Dashboard
    document.getElementById("pantalla-dashboard").style.display = "none";

    // Volver a mostrar la pantalla de login con su display original de tipo flex
    const pantallaLogin = document.querySelector(".pantalla-login");
    pantallaLogin.style.display = "flex";

    // Regresar a la vista de perfiles limpiamente
    mostrarPerfiles();
    
    window.scrollTo(0, 0);
}