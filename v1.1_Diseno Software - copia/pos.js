/* =========================================
   ESSENZA POS - LÓGICA DE CATÁLOGO Y CARRITO
========================================= */

let carrito = [];
let productoSeleccionadoActual = null;
let presentacionActual = { ml: 3, precio: 14.50 };

document.addEventListener("DOMContentLoaded", () => {
    inicializarFiltros();
    inicializarBotonesElegir();
    inicializarModalEventos();
    inicializarEventosCarrito();
});

/* =========================================
   FILTROS DE CATEGORÍA
========================================= */
function inicializarFiltros() {
    const botonesFiltro = document.querySelectorAll(".btn-filtro");
    botonesFiltro.forEach(boton => {
        boton.addEventListener("click", () => {
            botonesFiltro.forEach(b => b.classList.remove("activo"));
            boton.classList.add("activo");
        });
    });
}

/* =========================================
   INTERACCIÓN CON PRODUCTOS Y APERTURA DE MODAL
========================================= */
function inicializarBotonesElegir() {
    const tarjetasProducto = document.querySelectorAll(".card-producto");

    tarjetasProducto.forEach((card, index) => {
        card.addEventListener("click", () => {
            // Asignamos explícitamente la variable global
            productoSeleccionadoActual = {
                idBase: index,
                nombre: card.querySelector("h3").textContent,
                marca: card.querySelector(".marca-producto").textContent,
                tipo: card.querySelector(".label-tipo").textContent,
                imagen: card.querySelector(".cont-img-prod img").src,
                notas: Array.from(card.querySelectorAll(".tags-notas span")).map(span => span.textContent),
                precioBase: parseFloat(card.querySelector(".precio-cont strong").textContent.replace("$", ""))
            };

            abrirModal(productoSeleccionadoActual);
        });
    });
}

function abrirModal(prod) {
    document.getElementById("modal-marca").textContent = prod.marca;
    document.getElementById("modal-titulo").textContent = prod.nombre;
    document.getElementById("modal-tipo-desc").textContent = prod.tipo;
    document.getElementById("modal-img").src = prod.imagen;

    const contenedorTags = document.getElementById("modal-tags-notas");
    contenedorTags.innerHTML = "";
    prod.notas.forEach(nota => {
        contenedorTags.innerHTML += `<span>${nota}</span>`;
    });

    seleccionarMililitros(3, prod.precioBase);

    const modal = document.getElementById("modal-producto");
    if (modal) {
        modal.classList.remove("oculto");
    }
}

function seleccionarMililitros(ml, precioBase) {
    presentacionActual.ml = ml;
    
    if (ml === 5) {
        presentacionActual.precio = precioBase * 1.5;
    } else if (ml === 10) {
        presentacionActual.precio = precioBase * 2.65;
    } else {
        presentacionActual.precio = precioBase;
    }

    const precioFinalEl = document.getElementById("modal-precio-final");
    if (precioFinalEl) {
        precioFinalEl.textContent = `$${presentacionActual.precio.toFixed(2)}`;
    }

    document.querySelectorAll(".btn-ml").forEach(btn => {
        if (parseInt(btn.getAttribute("data-ml")) === ml) {
            btn.classList.add("activo");
        } else {
            btn.classList.remove("activo");
        }
    });
}

function inicializarModalEventos() {
    const modal = document.getElementById("modal-producto");
    const btnCerrar = document.getElementById("btn-cerrar-modal");
    const btnConfirmar = document.getElementById("btn-confirmar-agregar");

    if (!modal) return;

    // Cerrar con botón X
    if (btnCerrar) {
        btnCerrar.addEventListener("click", (e) => {
            e.stopPropagation();
            modal.classList.add("oculto");
        });
    }

    // Cerrar haciendo clic fuera de la tarjeta blanca
    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.add("oculto");
        }
    });

    // Cambiar presentación en ml
    document.querySelectorAll(".btn-ml").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            const ml = parseInt(btn.getAttribute("data-ml"));
            if (productoSeleccionadoActual) {
                seleccionarMililitros(ml, productoSeleccionadoActual.precioBase);
            } else {
                console.warn("Advertencia: No hay un producto seleccionado actualmente.");
            }
        });
    });

    // Confirmar y agregar al carrito
    if (btnConfirmar) {
        btnConfirmar.addEventListener("click", (e) => {
            e.stopPropagation();
            if (productoSeleccionadoActual) {
                const itemParaCarrito = {
                    id: `${productoSeleccionadoActual.idBase}-${presentacionActual.ml}ml`,
                    nombre: `${productoSeleccionadoActual.nombre} (${presentacionActual.ml} ml)`,
                    marca: productoSeleccionadoActual.marca,
                    precio: presentacionActual.precio,
                    cantidad: 1
                };

                agregarAlCarrito(itemParaCarrito);
                modal.classList.add("oculto");
            } else {
                console.warn("Advertencia: No se pudo agregar porque el producto es nulo.");
            }
        });
    }
}

/* =========================================
   GESTIÓN Y EVENTOS DEL CARRITO
========================================= */
function inicializarEventosCarrito() {
    const btnVaciar = document.getElementById("btn-vaciar-carrito");
    if (btnVaciar) {
        btnVaciar.addEventListener("click", () => {
            carrito = [];
            actualizarVistaCarrito();
        });
    }
}

function agregarAlCarrito(producto) {
    const indexExistente = carrito.findIndex(item => item.id === producto.id);

    if (indexExistente !== -1) {
        carrito[indexExistente].cantidad += 1;
    } else {
        carrito.push(producto);
    }

    actualizarVistaCarrito();
}

function cambiarCantidad(idItem, cambio) {
    const index = carrito.findIndex(item => item.id === idItem);
    if (index !== -1) {
        carrito[index].cantidad += cambio;
        if (carrito[index].cantidad <= 0) {
            carrito.splice(index, 1);
        }
    }
    actualizarVistaCarrito();
}

function eliminarItemCarrito(idItem) {
    carrito = carrito.filter(item => item.id !== idItem);
    actualizarVistaCarrito();
}

function actualizarVistaCarrito() {
    const carritoVacioMsg = document.getElementById("carrito-vacio-msg");
    const carritoLista = document.querySelector(".carrito-productos-lista");
    const cantProductosSpan = document.getElementById("cant-productos-carrito");
    const totalMontoStr = document.getElementById("total-carrito-monto");
    const btnCheckout = document.getElementById("btn-checkout");
    const btnVaciar = document.getElementById("btn-vaciar-carrito");

    const totalProductos = carrito.reduce((acc, item) => acc + item.cantidad, 0);
    if (cantProductosSpan) {
        cantProductosSpan.textContent = `${totalProductos}`;
    }

    if (carrito.length === 0) {
        if (carritoVacioMsg) carritoVacioMsg.classList.remove("oculto");
        if (carritoLista) {
            carritoLista.classList.add("oculto");
            carritoLista.innerHTML = "";
        }
        if (totalMontoStr) totalMontoStr.textContent = "$0.00";
        if (btnCheckout) btnCheckout.classList.add("deshabilitado");
        if (btnVaciar) btnVaciar.classList.add("oculto");
    } else {
        if (carritoVacioMsg) carritoVacioMsg.classList.add("oculto");
        if (carritoLista) carritoLista.classList.remove("oculto");
        if (btnVaciar) btnVaciar.classList.remove("oculto");

        let htmlItems = "";
        let subtotalGeneral = 0;

        carrito.forEach(item => {
            const subtotalItem = item.precio * item.cantidad;
            subtotalGeneral += subtotalItem;
            
            // Obtiene la inicial de la marca para el avatar
            const inicialMarca = item.marca ? item.marca.charAt(0).toUpperCase() : "P";

            htmlItems += `
                <div class="card-item-carrito">
                    <div class="item-carrito-header">
                        <div class="item-avatar-marca">${inicialMarca}</div>
                        <div class="item-carrito-detalles">
                            <p class="item-marca-label">${item.marca}</p>
                            <h4 class="item-titulo-pres">${item.nombre} · ${item.ml} ml</h4>
                            <span class="item-precio-unitario">$${item.precio.toFixed(2)} c/u</span>
                        </div>
                        <button class="btn-eliminar-item" type="button" onclick="eliminarItemCarrito('${item.id}')" title="Eliminar ítem">🗑️</button>
                    </div>

                    <div class="item-carrito-divider"></div>

                    <div class="item-carrito-footer">
                        <div class="controles-cantidad">
                            <button class="btn-cant" type="button" onclick="cambiarCantidad('${item.id}', -1)">−</button>
                            <span class="cant-val">${item.cantidad}</span>
                            <button class="btn-cant" type="button" onclick="cambiarCantidad('${item.id}', 1)">+</button>
                        </div>
                        <strong class="item-subtotal-monto">$${subtotalItem.toFixed(2)}</strong>
                    </div>
                </div>
            `;
        });

        if (carritoLista) carritoLista.innerHTML = htmlItems;
        if (totalMontoStr) totalMontoStr.textContent = `$${subtotalGeneral.toFixed(2)}`;
        if (btnCheckout) btnCheckout.classList.remove("deshabilitado");
    }
}