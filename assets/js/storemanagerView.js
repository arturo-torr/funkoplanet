import { validacionPago, validacionRegistro } from "./validation.js";

const EXECUTE_HANDLER = Symbol("executeHandler");

class StoreManagerView {
  constructor() {
    this.initzone = document.getElementById("init_zone");
    this.centralzone = document.getElementById("central_zone");
    this.nuevosProductos = document.getElementById("nuevos_productos");
    this.menu = document.querySelector(".navbar");
    this.offCanvasCarrito = new bootstrap.Offcanvas(
      document.getElementById("offCanvasCarrito")
    );
  }

  [EXECUTE_HANDLER](
    handler,
    handlerArguments,
    scrollElement,
    data,
    url,
    event
  ) {
    handler(...handlerArguments);
    const scroll = document.querySelector(scrollElement);
    if (scroll) scroll.scrollIntoView();
    history.pushState(data, null, url);
    event.preventDefault();
  }

  // Modificado el método para poder invocar a [EXECUTE_HANDLER]()
  bindInit(handler) {
    document.getElementById("init").addEventListener("click", (event) => {
      window.location.href = "/funkoplanet/index.php"; // Redirige directamente a la página de inicio
      this[EXECUTE_HANDLER](
        handler,
        [],
        "body",
        { action: "init" },
        "/funkoplanet/index.php",
        event
      );
    });
    document.getElementById("logo").addEventListener("click", (event) => {
      window.location.href = "/funkoplanet/index.php"; // Redirige directamente a la página de inicio
      this[EXECUTE_HANDLER](
        handler,
        [],
        "body",
        { action: "init" },
        "/funkoplanet/index.php",
        event
      );
    });
  }

  // Función que permite mostrar en el menú de navegación un ítem dropdown con las categorías
  showCategoriesInMenu(categories) {
    const navCats = document.getElementById("navCats");
    const container = navCats.nextElementSibling;
    //container.replaceChildren();
    for (const category of categories) {
      container.insertAdjacentHTML(
        "beforeend",
        `<li class="hover-menu"><a data-category="${category.id}" class="dropdown-item fw-bold" href="#">${category.name}</a></li>`
      );
    }
  }

  // Función que permite mostrar en el menú de navegación un ítem dropdown con los eventos
  showEventsInMenu(events) {
    const navEvents = document.getElementById("navEvents");
    const container = navEvents.nextElementSibling;
    container.replaceChildren();
    for (const event of events) {
      container.insertAdjacentHTML(
        "beforeend",
        `<li class="hover-menu"><a data-evento="${event.id}" class="dropdown-item fw-bold" href="#">${event.name}</a></li>`
      );
    }
  }

  // Función que tiene las herramientas necesarias para la administración de la página
  showAdminMenu() {
    // Crea un div y le asignamos formato de navegación
    const li = document.createElement("li");
    li.classList.add("nav-item", "dropdown");
    // Le insertamos el HTML que permite que sea dropdown
    li.insertAdjacentHTML(
      "beforeend",
      `<a
          class="nav-link dropdown-toggle fw-bold"
          href="#"
          id="navAdministration"
          role="button"
          data-bs-toggle="dropdown"
          aria-expanded="false">
          Administración
        </a>`
    );

    // Crea un ul y le asigna el formato que será el desplegable
    const subContainer = document.createElement("ul");
    subContainer.classList.add("dropdown-menu");
    subContainer.insertAdjacentHTML(
      "beforeend",
      '<li><a id="adminCategory" class="dropdown-item fw-bold" href="/funkoplanet/views/categories_crud.php">Categorías</a></li>'
    );
    subContainer.insertAdjacentHTML(
      "beforeend",
      '<li><a id="adminUsers" class="dropdown-item fw-bold" href="/funkoplanet/views/users_crud.php">Usuarios</a></li>'
    );
    subContainer.insertAdjacentHTML(
      "beforeend",
      '<li><a id="adminProds" class="dropdown-item fw-bold" href="/funkoplanet/views/productos_crud.php">Productos</a></li>'
    );
    subContainer.insertAdjacentHTML(
      "beforeend",
      '<li><a id="adminEvents" class="dropdown-item fw-bold" href="/funkoplanet/views/eventos_crud.php">Eventos</a></li>'
    );

    li.append(subContainer);

    // Inserta el menú de navegación creado
    let ul = document.querySelector("ul");
    ul.append(li);
  }

  // Manejadores para eventos de administración
  bindAdminMenu(adminCategory) {
    const categoryLink = document.getElementById("adminCategory");
    categoryLink.addEventListener("click", (event) => {
      this[EXECUTE_HANDLER](
        adminCategory,
        [],
        "#admin-category",
        { action: "adminCategory" },
        "#",
        event
      );
    });
  }

  // Función que selecciona las imágenes de los productos y cambia a la siguiente cuando se realiza un hover
  changeImagesInNewProducts() {
    let productImages = document.querySelectorAll(".product-image");
    productImages.forEach(function (image) {
      image.addEventListener("mouseover", function () {
        let nextImage = this.nextElementSibling;
        if (nextImage && nextImage.classList.contains("product-image")) {
          this.style.display = "none";
          nextImage.style.display = "block";
        }
      });
      image.addEventListener("mouseout", function () {
        let prevImage = this.previousElementSibling;
        if (prevImage && prevImage.classList.contains("product-image")) {
          this.style.display = "none";
          prevImage.style.display = "block";
        }
      });
    });
  }

  // Manejador que se da cuando se realiza click en la zona central de categorías
  bindCategoryList(handler) {
    // Obtiene el elemento y aquellos que dentro se compongan con el tag <a>
    const categoryList = document.getElementById("categories-list");
    if (categoryList) {
      const links = categoryList.querySelectorAll("a");
      // Los recorre y recupera el id de la categoría con el atributo personalizado dataset.category
      for (const link of links) {
        link.addEventListener("click", (event) => {
          const { category } = event.currentTarget.dataset;
          handler(category);
        });
      }
    }
  }

  // Manejador que se da cuando se realiza click en el menú item de reservas
  bindReservas(handler) {
    const navReservas = document.getElementById("navReservas");
    navReservas.addEventListener("click", () => handler("Reserva"));
    const footerReservas = document.getElementById("footer_reservas");
    footerReservas.addEventListener("click", () => handler("Reserva"));
  }

  // Función enlazadora que permite llamar al handler con la categoría
  bindCategoryListInMenu(handler) {
    const navCats = document.getElementById("categories-list-menu");
    const links = navCats.querySelectorAll("a");
    // Los recorre y recupera el id de la categoría con el atributo personalizado dataset.category
    for (const link of links) {
      link.addEventListener("click", (event) => {
        const { category } = event.currentTarget.dataset;
        handler(category);
      });
    }
  }

  // Función enlazadora que permite llamar al handler con el evento
  bindEventListInMenu(handler) {
    const navEvents = document.getElementById("events-list-menu");
    const links = navEvents.querySelectorAll("a");
    // Los recorre y recupera el id de la categoría con el atributo personalizado dataset.category
    for (const link of links) {
      link.addEventListener("click", (event) => {
        const { evento } = event.currentTarget.dataset;
        handler(evento);
      });
    }
  }

  // Función manejadora para los productos que tengamos
  bindProducts(handler) {
    const newProducts = document.getElementById("nuevos_productos");
    const products = document.getElementById("products");
    if (newProducts) {
      const links = newProducts.querySelectorAll("a");
      // Los recorre y recupera el id de la categoría con el atributo personalizado dataset.category
      for (const link of links) {
        link.addEventListener("click", (event) => {
          const { product } = event.currentTarget.dataset;
          handler(product);
        });
      }
    }
    if (products) {
      const links = products.querySelectorAll("a");
      // Los recorre y recupera el id de la categoría con el atributo personalizado dataset.category
      for (const link of links) {
        link.addEventListener("click", (event) => {
          const { product } = event.currentTarget.dataset;
          handler(product);
        });
      }
    }
  }

  // Aumenta la cantidad al clickear en el botón de incremento
  incrementarCantidad() {
    let cantidadSpan = document.getElementById("cantidad");
    let button = document.getElementById("btn_incremento");
    let disponibles = button.getAttribute("data-disponibles");
    let cantidad = parseFloat(cantidadSpan.innerText);
    //cantidad++;
    cantidad += 1;

    cantidad > disponibles ? cantidad-- : 0;
    cantidadSpan.innerHTML = cantidad;
  }

  // Disminuye la cantidad al clickear en el botón de decremento
  decrementarCantidad() {
    let cantidadSpan = document.getElementById("cantidad");
    let cantidad = parseFloat(cantidadSpan.innerText);

    if (cantidad > 1) {
      cantidad--;
      cantidadSpan.innerHTML = cantidad;
    }
  }

  // Manejador para cuando se clickea en botón de incremento
  bindIncrementButton(handler) {
    const button = document.getElementById("btn_incremento");
    if (button) button.addEventListener("click", () => handler());
  }

  // Manejador para cuando se clickea en botón de decremento
  bindDecrementButton(handler) {
    const button = document.getElementById("btn_decremento");
    if (button) button.addEventListener("click", () => handler());
  }

  // Manejador para cuando se clickea en botón de comprar producto
  bindCompraButton(handler) {
    const button = document.getElementById("btn_comprar");
    if (button) {
      let cantidad = document.getElementById("cantidad");
      button.addEventListener("click", (event) => {
        const { product } = event.currentTarget.dataset;
        handler(product, cantidad.textContent);
      });
    }
  }

  // Manejador para cuando se clcikea en botón de reservar producto
  bindReservaButton(handler) {
    const button = document.getElementById("btn_reservar");
    if (button) {
      let cantidad = document.getElementById("cantidad");
      button.addEventListener("click", (event) => {
        const { product } = event.currentTarget.dataset;
        handler(product, cantidad.textContent);
      });
    }
  }

  // Manejador para cuando se clickea SVG de carrito
  bindCarrito(handler) {
    const icon = document.getElementById("carrito");
    if (carrito) icon.addEventListener("click", () => handler());
  }

  // Manejadores que requieren de la validación del formulario
  bindCategoriesCrud(handler) {
    categoriesCrudValidation(handler);
  }

  // Muestra en el carrito el número de items que tiene
  mostrarCantidad() {
    let cantidad = localStorage.getItem("cantidades");
    if (!cantidad) {
      document.getElementById("numero_items_carrito").textContent = 0;
    } else {
      document.getElementById("numero_items_carrito").textContent = cantidad;
    }
  }

  // Actualiza el carrito cuando recibe nuevos items
  actualizarCarrito() {
    let num = document.getElementById("span_cantidades");
    if (!num) {
      document.getElementById("numero_items_carrito").textContent = 0;
    } else {
      localStorage.setItem("cantidades", num.textContent);
      let cantidad = localStorage.getItem("cantidades");
      document.getElementById("numero_items_carrito").textContent = cantidad;
    }
  }

  // Manejador que se da cuando se realiza click en el botón dentro del carrito
  bindFinalizarCompra(handler) {
    const button = document.getElementById("btn_finalizar");
    if (button) button.addEventListener("click", () => handler());
  }

  // Enlazador que se da cuando se clickea en el botón de incremento al finalizar la compra
  bindIncrementoFinalizarCompra(handler) {
    document.querySelectorAll(".btn_incremento").forEach((button) => {
      button.addEventListener("click", function () {
        const idProducto = this.getAttribute("data-id");
        handler(idProducto, 1);
      });
    });
  }

  // Enlazador que se da cuando se clickea en el botón de decremento al finalizar la compra
  bindDecrementoFinalizarCompra(handler) {
    document.querySelectorAll(".btn_decremento").forEach((button) => {
      button.addEventListener("click", function () {
        const idProducto = this.getAttribute("data-id");
        handler(idProducto, -1);
      });
    });
  }

  // Función que recibe los datos de una promesa php con las nuevas cantidades de un producto, calculando los totales y actualizando el text SVG del carrito
  actualizarPantallaFinalizacionCompra(data, idProducto) {
    if (data.success) {
      // Fila que corresponde al id del producto para mostrar los cambios
      const fila = document.querySelector(`tr[data-id='${idProducto}']`);

      // Si la nueva cantidad es 0, recarga la página para eliminar el item de la pantalla de carrito
      if (data.nuevaCantidad === 0) {
        location.reload();
      } else {
        // Span de cantidad
        const cantidadSpan = fila.querySelector(".cantidad");
        // Td que tiene el precio
        const precioTd = fila.querySelector(".precio");

        // Actualiza la cantidad correspondiente
        const nuevaCantidad = data.nuevaCantidad;
        cantidadSpan.textContent = "x" + nuevaCantidad;

        // Actualizar el precio correspondiente
        const nuevoPrecio = data.nuevoPrecio.toFixed(2) + "€";
        precioTd.textContent = nuevoPrecio;

        // Actualiza el total de la pantalla de finalización de compra
        document.getElementById("total").textContent =
          "Total: " + data.total.toFixed(2) + "€";
      }

      // Actualiza local storage para el text SVG del carrito
      localStorage.setItem("cantidades", data.totalCantidades);
      let cantidad = localStorage.getItem("cantidades");
      document.getElementById("numero_items_carrito").textContent = cantidad;
    }
  }

  // Modal que se muestra cuando el usuario intenta comprar un producto sin estar logueado
  necesarioLoginModal() {
    let modal = new bootstrap.Modal(document.getElementById("modal_usuario"));
    modal.show();
  }

  // Muestra el modal cuando un pedido se ha completado correctamente
  pedidoRealizadoModal() {
    let modal = new bootstrap.Modal(document.getElementById("pedidoRealizado"));
    modal.show();

    // Botones de cerrado del modal
    let closeButton = document.getElementById("modal-close");
    let footerCloseButton = document.getElementById("btn_cerrar_modal");

    // Función para redirigir
    let redirigir = () => {
      window.location.href = `/funkoplanet/index.php`;
    };

    // Cuando se clickea en cualquiera de las partes para cerrar, redirige al índice
    closeButton.addEventListener("click", redirigir);
    footerCloseButton.addEventListener("click", redirigir);
  }

  // Manejador que permite en la pantalla de finalización de compra pasar de una parte del acordeón a la otra
  bindButtonPago() {
    let button = document.getElementById("btn_pago");
    if (button) {
      button.addEventListener("click", function () {
        let headerButton = document.getElementById("header_pago_button");
        if (headerButton) {
          headerButton.disabled = false;
          let collapseTwo = new bootstrap.Collapse(
            document.getElementById("collapseTwo"),
            {
              toggle: true,
            }
          );
        }
      });
    }
  }

  // Enlazador con el botoón de finalización de compra con el controlador
  bindButtonFinalizarCompra(handler) {
    let button = document.getElementById("btn_realizar_compra");
    if (button) {
      button.addEventListener("click", function () {
        handler();
      });
    }
  }

  // Enlazador que redirige al apartado de mis pedidos
  bindMisPedidos(handler) {
    let enlace = document.getElementById("mis_pedidos");
    if (enlace) {
      enlace.addEventListener("click", () => handler());
    }
  }

  // Enlazador que redirige al apartado de mis reservas
  bindMisReservas(handler) {
    let enlace = document.getElementById("mis_reservas");
    if (enlace) {
      enlace.addEventListener("click", () => handler());
    }
  }

  // VALIDACIONES
  bindValidacionPago(handler) {
    validacionPago(handler);
  }

  bindValidacionRegistro(handler) {
    validacionRegistro(handler);
  }
}

export default StoreManagerView;
