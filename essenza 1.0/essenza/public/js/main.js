/* =========================================
   ESTADO GLOBAL
========================================= */
let perfilSeleccionado = null; // Ahora guardamos el objeto del usuario, no el index

// Al cargar la página, consultamos si hay usuarios en la BD para mostrarlos
document.addEventListener("DOMContentLoaded", () => {
    mostrarPerfiles();
});

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
    
    cargarPerfilesDesdeBD();
}

/* =========================================
   CARGAR PERFILES DESDE SQLITE
========================================= */
async function cargarPerfilesDesdeBD() {
    const contenedorPerfiles = document.getElementById("contenedor-perfiles");
    try {
        // Asumiendo que hacemos una petición para traer usuarios
        // Como no hicimos un endpoint para "listar" usuarios en api.php, 
        // podemos simularlo o hacer un endpoint rápido. 
        // Para no enredar más, hagamos que el registro sea fluido:
        
        // NOTA: En un POS real, no deberías listar todos los usuarios sin protección.
        // Pero para efectos de este proyecto escolar, haremos la consulta directa.
        const respuesta = await fetch('api.php?accion=listar_usuarios');
        const resultado = await respuesta.json();
        
        const usuarios = resultado.ok ? resultado.data : [];

        if (usuarios.length === 0) {
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
        usuarios.forEach((perfil) => {
            const inicial = perfil.nombre.charAt(0).toUpperCase();
            htmlTarjetas += `
                <div class="tarjeta-perfil" onclick='seleccionarPerfil(${JSON.stringify(perfil)})'>
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
    } catch (error) {
        console.error("Error al cargar perfiles:", error);
    }
}

/* =========================================
   SELECCIONAR PERFIL Y PREPARAR LOGIN
========================================= */
function seleccionarPerfil(perfil) {
    perfilSeleccionado = perfil; // Guardamos el objeto del usuario

    document.getElementById("login-nombre-usuario").textContent = perfil.nombre;
    document.getElementById("login-avatar").textContent = perfil.nombre.charAt(0).toUpperCase();
    document.getElementById("contrasena-login").value = "";
    
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
   VALIDACIÓN Y CREACIÓN DE PERFIL (BD)
========================================= */
async function validarRegistro() {
    const nombre = document.getElementById("nombre-usuario").value.trim();
    const correo = document.getElementById("correo-usuario").value.trim();
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

    // Enviamos a la API
    try {
        const respuesta = await fetch('api.php?accion=registrar_usuario', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, correo, contrasena })
        });
        const resultado = await respuesta.json();

        if (resultado.ok) {
            mostrarPerfiles(); // Recargamos la lista
        } else {
            textoAlerta.textContent = resultado.error;
            alerta.style.display = "flex";
        }
    } catch (error) {
        console.error("Error al registrar:", error);
    }
}

/* =========================================
   INICIO DE SESIÓN (BD)
========================================= */
async function iniciarSesion() {
    const pass = document.getElementById("contrasena-login").value.trim();
    const alerta = document.getElementById("alerta-login");
    const textoAlerta = document.getElementById("texto-alerta-login");

    if (pass === "") {
        textoAlerta.textContent = "Ingresa tu contraseña para continuar.";
        alerta.style.display = "flex";
        return;
    }

    try {
        const respuesta = await fetch('api.php?accion=login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre: perfilSeleccionado.nombre, contrasena: pass })
        });
        const resultado = await respuesta.json();

        if (resultado.ok) {
            alerta.style.display = "none";
            document.querySelector(".pantalla-login").style.display = "none";

            document.getElementById("dashboard-usuario-nombre").textContent = perfilSeleccionado.nombre;
            document.getElementById("dashboard-avatar").textContent = perfilSeleccionado.nombre.charAt(0).toUpperCase();

            document.getElementById("pantalla-dashboard").style.display = "flex";
            window.scrollTo(0, 0);
        } else {
            textoAlerta.textContent = resultado.error;
            alerta.style.display = "flex";
        }
    } catch (error) {
        console.error("Error en login:", error);
    }
}

function cerrarSesion() {
    document.getElementById("pantalla-dashboard").style.display = "none";
    const pantallaLogin = document.querySelector(".pantalla-login");
    pantallaLogin.style.display = "flex";
    mostrarPerfiles();
    window.scrollTo(0, 0);
}
// Escuchar el envío de los formularios
document.addEventListener("DOMContentLoaded", () => {
    
    // 1. Escuchar formulario de REGISTRO
    const formRegistro = document.getElementById("form-registro");
    if (formRegistro) {
        formRegistro.addEventListener("submit", (e) => {
            e.preventDefault(); // Evita que la página se recargue
            validarRegistro();  
        });
    }

    // 2. Escuchar formulario de LOGIN
    const formLogin = document.getElementById("form-login");
    if (formLogin) {
        formLogin.addEventListener("submit", (e) => {
            e.preventDefault(); // Evita que la página se recargue
            iniciarSesion();    
        });
    }
});