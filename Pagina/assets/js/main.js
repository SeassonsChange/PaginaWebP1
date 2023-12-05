document.addEventListener("DOMContentLoaded", (event) => {
    const cookies = document.cookie.split(";");
    let cookie = null;
    cookies.forEach((item) => {
        if (item.indexOf("items") > -1) {
            cookie = item;
        }
    });

    if (cookie != null) {
        const count = cookie.split("=")[1];
        console.log(count);
        document.querySelector(".btn-carrito").innerHTML = `(${count}) Carrito`;
    }
});

const bCarrito = document.querySelector(".btn-carrito");

bCarrito.addEventListener("click", (event) => {
    const carritoContainer = document.querySelector("#carrito-container");

    if (carritoContainer.style.display == "") {
        carritoContainer.style.display = "block";
        actualizarCarritoUI();
    } else {
        carritoContainer.style.display = "";
    }
});

function actualizarCarritoUI() {
    fetch("http://localhost/Pagina/modelos/api-carrito.php?action=mostrar")
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            let tablaCont = document.querySelector("#tabla");
            let precioTotal = "";
            let html = "";
            let btnFinal = "";

            data.items.forEach((element) => {
                html += `
                <div class = 'fila'>
                    <div class='imagen'>
                            <img src='../assets/imagenes/${element.Url}' width='100' />
                    </div>
                    
                        <div class = 'info'>
                            <input type='hidden' value='${element.idCarro}' />
                            <div class='nombre'>${element.Nombre}</div>
                            <div>${element.cantidad} items de $${element.Precio}</div>
                            <div>Subtotal: $${element.subtotal}</div>
                            <div class='botones'><button class='btn-remove'>Quitar 1 del carrito</button></div>
                        </div>
                </div>
                `;
            });

            btnFinal = `
                <div class ='fila'>
                    <div class='info'>
                        <div class='botones'><button class='btn-final'>Finalizar Compra</button></div>
                    </div>
                </div>
                `;

            precioTotal = `<p>Total: $${data.info.total}</p>`;
            tablaCont.innerHTML = precioTotal + html + btnFinal;

            document.cookie = `items=${data.info.count}`;

            bCarrito.innerHTML = `(${data.info.count}) Carrito`;

            document.querySelectorAll(".btn-remove").forEach((boton) => {
                boton.addEventListener("click", (e) => {
                    const id = boton.parentElement.parentElement.children[0].value;
                    removeItemFromCarrito(id);
                });
            });

            const botonComprar = document.querySelector(".btn-final");
            console.log("Elemento: ", botonComprar);

            botonComprar.addEventListener("click", (event) => {
                obtenerItemsEnCarrito()
                    .then((itemsEnCarrito) => {
                        enviarCompra(itemsEnCarrito);
                    })
                    .catch((error) => {
                        console.error("Error al recuperar los itemsEnCarrito.", error);
                    });
                    actualizarCarritoUIVacio();
            });
        });
}


function actualizarCarritoUIVacio(){
    
}


const botones = document.querySelectorAll(".btn-add");

botones.forEach((boton) => {
    const id = boton.parentElement.parentElement.children[0].value;

    boton.addEventListener("click", (e) => {
        addItemCarrito(id);
    });
});

document.addEventListener("DOMContentLoaded", function () { });

function obtenerItemsEnCarrito() {
    return new Promise((resolve, reject) => {
        fetch("http://localhost/Pagina/modelos/api-carrito.php?action=mostrar")
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
                items = [];

                data.items.forEach((element) => {
                    const id = element.idCarro;
                    const cantidad = element.cantidad;

                    items.push({ id, cantidad });
                });

                resolve(items);
            })
            .catch((error) => {
                console.error("Error en la solicitud fetch: ", error);
                reject(error);
            });
    });
}

function enviarCompra(itemsEnCarrito) {
    console.log("Enviando compra con items: ", itemsEnCarrito);
    fetch("http://localhost/Pagina/modelos/api-carrito.php?action=terminar", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ items: itemsEnCarrito }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Error de red: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Respuesta de la API: ", data);
            actualizarCarritoUI();
        })
        .catch((error) => {
            console.error("Error al finalizar la compra", error);
            console.log("Respuesta completa: ", error);
        });
}

function removeItemFromCarrito(id) {
    fetch(
        "http://localhost/Pagina/modelos/api-carrito.php?action=remove&id=" + id
    )
        .then((res) => res.json())
        .then((data) => {
            console.log(data.status);
            actualizarCarritoUI();
        });
}

function addItemCarrito(id) {
    fetch("http://localhost/Pagina/modelos/api-carrito.php?action=add&id=" + id)
        .then((res) => res.json())
        .then((data) => {
            console.log(data.status);
            actualizarCarritoUI();
        });
}
