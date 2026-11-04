<?php
// Esto prepara la conexión a la base de datos para todo el archivo
require __DIR__ . '/../src/database.php';
$db = getDB(); 
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>ESSENZA - Decants & Parfums</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" type="text/css" href="css/Estilo_index.css"/>
    </head>

    <body>

        <!-- ==================================
            PANTALLA 0: PANTALLA LOGIN
        =================================== -->
        <div class="pantalla-login">

            <!-- PANEL IZQUIERDO -->
            <section class="panel-izquierdo">

                <!-- LOGOTIPO -->
                <div class="logo">
                    <div class="logo-icono">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8L12 2z"/>
                            <path d="M19 15l1 3 3 1-3 1-1 3-1-3-3-1 3-1 1-3z"/>
                        </svg>
                    </div>

                    <div class="logo-texto">
                        <h2>ESSENZA</h2>
                        <span>DECANTS & PARFUMS</span>
                    </div>
                </div>

                <!-- DECORACIÓN -->
                <div class="circulo circulo-superior"></div>
                <div class="circulo circulo-inferior"></div>

                <!-- CONTENIDO PRINCIPAL -->
                <div class="contenido-izquierdo">

                    <p class="etiqueta-izquierda">
                        PERFUMERÍA DE AUTOR · POS
                    </p>

                    <h1>
                        Cada<br>
                        esencia,<br>
                        <span>a tu medida.</span>
                    </h1>

                    <p class="descripcion">
                        Gestiona tu colección de decants, consulta fragancias y
                        completa cada venta con precisión.
                    </p>

                </div>

                <!-- PIE DEL PANEL IZQUIERDO -->
                <footer class="footer-izquierdo">
                    <span>Venta rápida y organizada</span>
                    <span>EST. 2026</span>
                </footer>

            </section>


            <!-- PANEL DERECHO -->
            <section class="panel-derecho">

                <!-- ENCABEZADO -->
                <header class="encabezado-derecho">
                    <div class="estado-terminal">
                        <span class="punto-activo"></span>
                        TERMINAL ACTIVA
                    </div>
                </header>


                <!-- ==================================
                    PANTALLA 1: SELECCIÓN DE PERFILES
                =================================== -->

                <main class="contenido-derecho" id="pantalla-perfiles">

                    <p class="etiqueta-derecha">
                        ACCESO AL PUNTO DE VENTA
                    </p>

                    <h2>¿Quién está en turno?</h2>

                    <p class="subtitulo">
                        Selecciona tu perfil para ingresar de forma segura.
                    </p>

                    <!-- Contenedor dinámico para la tarjeta de perfil y el estado vacío -->
                    <div id="contenedor-perfiles">
                        <div class="sin-perfiles" id="mensaje-sin-perfiles">
                            <svg class="icono-usuario" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a7 7 0 0 0-14 0v2"/>
                                <circle cx="13" cy="7" r="4"/>
                            </svg>
                            <h3>Aún no hay perfiles</h3>
                            <p>Crea la primera cuenta para abrir la terminal.</p>
                        </div>
                    </div>

                    <button class="btn-crear-cuenta"
                            type="button"
                            onclick="mostrarRegistro()">

                        <span class="icono-mas">+</span>
                        Crear cuenta nueva

                    </button>

                </main>


                <!-- ==================================
                    PANTALLA 2: CREAR CUENTA
                =================================== -->

                <main class="contenido-registro" id="pantalla-registro">

                    <!-- VOLVER A PERFILES -->
                    <button class="btn-volver"
                            type="button"
                            onclick="mostrarPerfiles()">

                        <span>←</span>
                        Volver a perfiles

                    </button>


                    <!-- TÍTULO -->
                    <div class="encabezado-registro">

                        <p class="etiqueta-derecha">
                            NUEVO INTEGRANTE
                        </p>

                        <h2>Crear una cuenta</h2>

                        <p class="subtitulo">
                            Configura un acceso personal para operar la tienda.
                        </p>

                    </div>


                    <!-- FORMULARIO DE REGISTRO -->
                    <form class="formulario-registro" id="form-registro">

                        <!-- NOMBRE DE USUARIO -->
                        <div class="grupo-input">

                            <label for="nombre-usuario">
                                Nombre de usuario <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="nombre-usuario"
                                placeholder="Ej. Valentina"
                                required
                            >

                        </div>


                        <!-- CORREO -->
                        <div class="grupo-input">

                            <label for="correo-usuario">
                                Correo <span class="opcional">(opcional)</span>
                            </label>

                            <div class="contenedor-input">

                                <svg viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="m3 7 9 6 9-6"/>

                                </svg>

                                <input
                                    type="email"
                                    id="correo-usuario"
                                    placeholder="nombre@tienda.com"
                                >

                            </div>

                        </div>


                        <!-- CONTRASEÑA -->
                        <div class="grupo-input">

                            <label for="contrasena-usuario">
                                Contraseña <span>*</span>
                            </label>

                            <div class="contenedor-input">

                                <svg viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <rect x="4" y="10" width="16" height="11" rx="2"/>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                    <circle cx="12" cy="15" r="1"/>

                                </svg>

                                <input
                                    type="password"
                                    id="contrasena-usuario"
                                    placeholder="Mínimo 4 caracteres"
                                    minlength="4"
                                    required
                                >

                                <button type="button"
                                        class="btn-ver-contrasena"
                                        onclick="alternarContrasena()"
                                        aria-label="Mostrar u ocultar contraseña">

                                    <svg viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                        <circle cx="12" cy="12" r="3"/>

                                    </svg>

                                </button>

                            </div>

                        </div>


                        <!-- BOTÓN REGISTRAR Y ALERTA -->
                        <div class="grupo-input">
                            <div id="alerta-registro" class="alerta-error" style="display: none;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                    <line x1="12" y1="9" x2="12" y2="13"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                                <span id="texto-alerta">Completa el nombre y la contraseña.</span>
                            </div>
                        </div>

                        <button class="btn-registrar" type="submit">
                            <span class="icono-mas">+</span>
                            Crear cuenta
                        </button>

                    </form>

                </main>


                <!-- ==================================
                    PANTALLA 3: LOGIN CON CONTRASEÑA
                =================================== -->

                <main class="contenido-login-usuario" id="pantalla-login-usuario" style="display: none;">

                    <!-- VOLVER A PERFILES -->
                    <button class="btn-volver" type="button" onclick="mostrarPerfiles()">
                        <span>←</span>
                        Cambiar de usuario
                    </button>

                    <!-- ENCABEZADO CON AVATAR Y NOMBRE -->
                    <div class="encabezado-usuario-login">
                        <div class="avatar-inicial avatar-grande" id="login-avatar">A</div>
                        <div class="info-usuario-login">
                            <p class="etiqueta-derecha">BIENVENIDO DE VUELTA</p>
                            <h2 id="login-nombre-usuario">admin</h2>
                        </div>
                    </div>

                    <!-- FORMULARIO DE CONTRASEÑA -->
                    <form class="formulario-registro" id="form-login">

                        <div class="grupo-input">
                            <label for="contrasena-login">Contraseña</label>
                            <div class="contenedor-input">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="10" width="16" height="11" rx="2"/>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                    <circle cx="12" cy="15" r="1"/>
                                </svg>
                                <input type="password" id="contrasena-login" placeholder="Ingresa tu contraseña">
                                <button type="button" class="btn-ver-contrasena" onclick="alternarContrasenaLogin()" aria-label="Mostrar u ocultar contraseña">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- CUADRO DE ALERTA DE ERROR -->
                        <div id="alerta-login" class="alerta-error" style="display: none;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <span id="texto-alerta-login">Ingresa tu contraseña para continuar.</span>
                        </div>

                        <button class="btn-registrar" type="submit">
                            Ingresar al POS →
                        </button>

                    </form>

                    <!-- NOTA DE SEGURIDAD -->
                    <div class="nota-seguridad">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <polyline points="9 12 11 14 15 10"/>
                        </svg>
                        <span>Sesión protegida en este dispositivo</span>
                    </div>

                </main>


                <!-- PIE DE PÁGINA -->
                <footer class="footer-derecho">

                    <span>ESSENZA POS</span>
                    <span>VERSIÓN 1.0</span>

                </footer>

            </section>

        </div>


        <!-- ==================================
            PANTALLA 4: DASHBOARD / MENÚ PRINCIPAL
        =================================== -->
        <div id="pantalla-dashboard" class="pantalla-dashboard" style="display: none;">

            <!-- ENCABEZADO SUPERIOR DEL DASHBOARD -->
            <header class="header-dashboard">
                <div class="logo">
                    <div class="logo-icono">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8L12 2z"/>
                            <path d="M19 15l1 3 3 1-3 1-1 3-1-3-3-1 3-1 1-3z"/>
                        </svg>
                    </div>
                    <div class="logo-texto">
                        <h2>ESSENZA</h2>
                        <span>DECANTS & PARFUMS</span>
                    </div>
                </div>

                <div class="usuario-header">
                    <button class="btn-notificacion" type="button" aria-label="Notificaciones">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </button>

                    <div class="perfil-header">
                        <div class="avatar-inicial" id="dashboard-avatar">A</div>
                        <div class="info-turno">
                            <span>En turno</span>
                            <strong id="dashboard-usuario-nombre">admin</strong>
                        </div>
                    </div>

                    <button class="btn-salir" type="button" onclick="cerrarSesion()" title="Cerrar sesión">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- CONTENIDO PRINCIPAL DASHBOARD -->
            <main class="contenido-dashboard">

                <!-- BANNER PROMINENTE -->
                <section class="banner-hero">
                    <div class="banner-info">
                        <p class="etiqueta-banner">MENÚ PRINCIPAL · ESSENZA POS</p>
                        <h1>Encuentra la esencia<br><em>perfecta, más rápido.</em></h1>
                        <p class="descripcion-banner">Consulta el catálogo, compara presentaciones y gestiona cada pedido desde un solo lugar.</p>
                        
                        <button class="btn-explorar" type="button">
                            Explorar catálogo →
                        </button>
                    </div>

                    <div class="banner-decoracion">
                        <!-- Silueta decorativa de frasco de perfume -->
                        <svg viewBox="0 0 200 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="70" y="20" width="60" height="35" rx="4" stroke="rgba(231, 189, 114, 0.4)" stroke-width="2"/>
                            <path d="M40 85C40 68.4315 53.4315 55 70 55H130C146.569 55 160 68.4315 160 85V210C160 221.046 151.046 230 140 230H60C48.9543 230 40 221.046 40 210V85Z" stroke="rgba(231, 189, 114, 0.3)" stroke-width="2"/>
                            <text x="100" y="150" text-anchor="middle" fill="rgba(231, 189, 114, 0.5)" font-size="12" letter-spacing="4" font-family="Georgia">ESSENZA</text>
                        </svg>
                    </div>
                </section>

                <!-- NAVEGACIÓN DE MÓDULOS -->
                <section class="seccion-modulos">
                    <div class="encabezado-seccion">
                        <div>
                            <span class="etiqueta-derecha">NAVEGACIÓN</span>
                            <h2>Opciones principales</h2>
                        </div>
                        <span class="subtexto-seccion">Selecciona un módulo para comenzar</span>
                    </div>

                    <div class="grid-modulos">
                        <!-- MÓDULO 1: CATÁLOGO (ACTIVO) -->
                        <div class="card-modulo modulo-destacado">
                            <div class="card-modulo-header">
                                <div class="icono-modulo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                        <line x1="3" y1="6" x2="21" y2="6"/>
                                        <path d="M16 10a4 4 0 0 1-8 0"/>
                                    </svg>
                                </div>
                                <div class="flecha-modulo">→</div>
                            </div>
                            <span class="categoria-modulo">MÓDULO PRINCIPAL</span>
                            <h3>Catálogo y venta</h3>
                            <p>Consulta fragancias, presentaciones, precios y disponibilidad.</p>
                        </div>

                        <!-- MÓDULO 2: PEDIDOS -->
                        <div class="card-modulo">
                            <div class="card-modulo-header">
                                <div class="icono-modulo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    </svg>
                                </div>
                                <span class="badge-proximamente">PRÓXIMAMENTE</span>
                            </div>
                            <span class="categoria-modulo">OPERACIÓN</span>
                            <h3>Pedidos</h3>
                            <p>Revisa compras en curso, entregas y pedidos completados.</p>
                        </div>

                        <!-- MÓDULO 3: INVENTARIO -->
                        <div class="card-modulo">
                            <div class="card-modulo-header">
                                <div class="icono-modulo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                    </svg>
                                </div>
                                <span class="badge-proximamente">PRÓXIMAMENTE</span>
                            </div>
                            <span class="categoria-modulo">EXISTENCIAS</span>
                            <h3>Inventario</h3>
                            <p>Controla existencias por fragancia y tamaño de decant.</p>
                        </div>

                        <!-- MÓDULO 4: CLIENTES -->
                        <div class="card-modulo">
                            <div class="card-modulo-header">
                                <div class="icono-modulo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <span class="badge-proximamente">PRÓXIMAMENTE</span>
                            </div>
                            <span class="categoria-modulo">RELACIONES</span>
                            <h3>Clientes</h3>
                            <p>Consulta perfiles, preferencias e historial de compra.</p>
                        </div>

                        <!-- MÓDULO 5: REPORTES -->
                        <div class="card-modulo">
                            <div class="card-modulo-header">
                                <div class="icono-modulo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <line x1="18" y1="20" x2="18" y2="10"/>
                                        <line x1="12" y1="20" x2="12" y2="4"/>
                                        <line x1="6" y1="20" x2="6" y2="14"/>
                                    </svg>
                                </div>
                                <span class="badge-proximamente">PRÓXIMAMENTE</span>
                            </div>
                            <span class="categoria-modulo">INDICADORES</span>
                            <h3>Reportes</h3>
                            <p>Analiza ventas, fragancias favoritas y rendimiento.</p>
                        </div>

                        <!-- MÓDULO 6: CONFIGURACIÓN -->
                        <div class="card-modulo">
                            <div class="card-modulo-header">
                                <div class="icono-modulo">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                    </svg>
                                </div>
                                <span class="badge-proximamente">PRÓXIMAMENTE</span>
                            </div>
                            <span class="categoria-modulo">SISTEMA</span>
                            <h3>Configuración</h3>
                            <p>Ajusta la tienda, usuarios, precios y preferencias.</p>
                        </div>
                    </div>
                </section>

            </main>

        </div>

        <!-- Al final, llamamos al JS -->
        <script src="js/main.js"></script>

    </body>

</html>