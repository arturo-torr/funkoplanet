// Importado de los módulos necesarios
import StoreManager, { Category, Evento } from "./storemanager.js";

// Modelo y vista como constantes privadas
const MODEL = Symbol("StoreManagerModel");
const VIEW = Symbol("StoreManagerView");

class StoreManagerController {
  constructor(model, view) {
    this[MODEL] = model;
    this[VIEW] = view;
    this.onLoad();
    this.onInit();
    this[VIEW].bindInit(this.handleInit);
  }

  // Funciones que solo se ejecutan una sola vez
  onLoad = () => {
    this.checkUserRole();
    this.onAddCategory();
    this.onAddEvent();
    this[VIEW].bindProducts(this.handleProduct);
    this[VIEW].bindReservas(this.handleReservas);
    this[VIEW].bindIncrementButton(this.handleIncrement);
    this[VIEW].bindDecrementButton(this.handleDecrement);
    this[VIEW].bindCompraButton(this.handleCompra);
    this[VIEW].bindReservaButton(this.handleCompra);
    this[VIEW].bindCarrito(this.handleCarrito);
    this[VIEW].bindIncrementoFinalizarCompra(this.handleCantidadesFinCompra);
    this[VIEW].bindDecrementoFinalizarCompra(this.handleCantidadesFinCompra);
    this[VIEW].changeImagesInNewProducts();
    this[VIEW].mostrarCantidad();
  };

  // Función que se ejecuta al cargar la página
  onInit = () => {};

  // Manejador de inicio
  handleInit = () => {
    this.onInit();
  };

  // Mostrado de categorías en navegación
  onAddCategory = () => {
    this.handleCategoriesInMenu()
      .then((categorias) => {
        this[VIEW].showCategoriesInMenu(categorias);
        this[VIEW].bindCategoryListInMenu(this.handleCategoryList);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
    this[VIEW].bindCategoryList(this.handleCategoryList);
  };

  // Mostrado de eventos en navegación
  onAddEvent = () => {
    this.handleEventsInMenu()
      .then((eventos) => {
        this[VIEW].showEventsInMenu(eventos);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  };

  // Comprueba el rol que tiene el usuario
  checkUserRole = () => {
    fetch("/funkoplanet/web/usuarioactual.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("No se pudo obtener la información del usuario");
        }
        return response.json();
      })
      .then((usuario) => {
        if (usuario && usuario.rol && usuario.rol === "A") {
          // Si el usuario es administrador, mostrar el menú de administración
          this[VIEW].showAdminMenu();
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  };

  // Manejador que redirige hacia la vista php con el id de la categoría
  handleCategoryList = (id) => {
    window.location.href = `/funkoplanet/web/controlador_categorias.php?paramCategorias=categoryClicked&id=${id}`;
  };

  // Maneajdor que redigige hacia la vista php con el id del producto
  handleProduct = (id) => {
    window.location.href = `/funkoplanet/web/controlador_productos.php?parametro=productClicked&id=${id}`;
  };

  // Manejaador que redirige hacia la vista php con las reservas
  handleReservas = (label) => {
    window.location.href = `/funkoplanet/views/products.php?category=&disponibilidad=${label}&orden=nuevos&Filtrar=Filtrar`;
  };

  // Manejador qeu redirige hacia la vista php para finalizar la compra con los items del carrito
  handleFinalizar = () => {
    window.location.href = `/funkoplanet/web/controlador_productos.php?parametro=finalizarCompra`;
  };

  // Manejador que devuelve una promesa permitiendo cargar las categorías en la barra de navegación
  handleCategoriesInMenu = () => {
    return new Promise((resolve, reject) => {
      fetch("/funkoplanet/web/controlador_categorias.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `paramCategorias=${"categoriesMenu"}`,
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error al obtener las categorías");
          }
          return response.json();
        })
        .then((data) => {
          const categories = data.map((categoria) => {
            let cat = this[MODEL].createCategory(categoria.nombre, "Category");
            cat.description = categoria.descripcion;
            cat.id = categoria.id;
            this[MODEL].addCategory(cat);
            return cat;
          });
          resolve(categories);
        })
        .catch((error) => {
          reject(error);
        });
    });
  };

  // Manejador que devuelve una promesa permitiendo cargar los eventos en la barra de navegación
  handleEventsInMenu = () => {
    return new Promise((resolve, reject) => {
      fetch("/funkoplanet/web/controlador_eventos.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `parametro=${"eventsMenu"}`,
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error al obtener los Eventos");
          }
          return response.json();
        })
        .then((data) => {
          const eventos = data.map((evento) => {
            let event = this[MODEL].createEvent(evento.nombre, "Evento");
            event.description = evento.descripcion;
            this[MODEL].addEvent(event);
            return event;
          });
          resolve(eventos);
        })
        .catch((error) => {
          reject(error);
        });
    });
  };

  // Manejador que se da cuando se ha realizado click en botones de incrementar o decrementar en pantalla de finalización de compra
  handleCantidadesFinCompra = (idProducto, cambio) => {
    fetch("actualizar_carrito.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        idProducto: idProducto,
        cambio: cambio,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          this[VIEW].actualizarPantallaFinalizacionCompra(data, idProducto);
        } else {
          console.error("Error al actualizar el carrito");
        }
      })
      .catch((error) => console.error("Error:", error));
  };

  // Manejador que abre el carrito de la compra con los productos de la sesión
  handleCarrito = () => {
    fetch("/funkoplanet/web/controlador_productos.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `parametro=${"productoCarrito"}`,
    })
      .then((response) => response.text())
      .then((html) => {
        let body = document.querySelector(".offcanvas-body");
        body.innerHTML = html;
        this[VIEW].offCanvasCarrito.show();
        this[VIEW].actualizarCarrito();
        this[VIEW].bindFinalizarCompra(this.handleFinalizar);
      })
      .catch((error) => {
        console.error("Error al actualizar el offCanvas:", error);
      });
  };

  // Manejador que recibe el id de producto y la cantidad para actualizar el carrito
  handleCompra = (idProducto, cantidad) => {
    // Pasa a numero la cantidad
    cantidad = Number(cantidad);

    fetch("/funkoplanet/web/procesar_carrito.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      // El cuerpo de la solicitud es el ID del producto y la cantidad solicitada
      body: JSON.stringify({
        idProducto: idProducto,
        cantidad: cantidad,
      }),
    })
      .then((response) => {
        // Si no es ok lanza error para poder mostrar posteriormente el modal o el error
        if (!response.ok) {
          return response.json().then((err) => {
            throw err;
          });
        }
        return response.json();
      })
      .then((data) => {
        // Llama al manejador de carrito para actualizarlo
        this.handleCarrito();
      })
      .catch((error) => {
        if (error.necesitaAutenticacion) {
          this[VIEW].necesarioLoginModal();
        } else {
          console.error("Error no esperado: ", error);
        }
      });
  };

  // Maneja el botón de incremento de cantidad de producto
  handleIncrement = () => {
    this[VIEW].incrementarCantidad();
  };

  // Maneja el botón de decremento de cantidad de producto
  handleDecrement = () => {
    this[VIEW].decrementarCantidad();
  };
}

export default StoreManagerController;
