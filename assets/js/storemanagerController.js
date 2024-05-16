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
    this[VIEW].bindIncrementButton(this.handleIncrement);
    this[VIEW].bindDecrementButton(this.handleDecrement);
    this[VIEW].bindCategoriesCrud(this.handleCategoriesCrud);
    this[VIEW].changeImagesInNewProducts();
    // this[VIEW].bindAdminMenu(this.handleNewCategoryForm);
  };

  // Manejador que recibe los datos de la validación del formulario cuando son correctos
  handleCategoriesCrud = (
    button,
    selects,
    nombre = "",
    descripcion = "",
    foto = ""
  ) => {
    if (button === "Buscar") {
      // Objeto literal para los datos
      const data = {
        nombreNuevo: nombre,
        Buscar: "Buscar",
      };

      // Convertir los datos a una cadena de consulta
      const params = new URLSearchParams(data);

      // URL donde se enviarán los datos
      const url = "/funkoplanet/views/categories_crud.php";

      // Agregar los parámetros de búsqueda a la URL de redirección
      const redirectUrl = url + "?" + params.toString() + "#busqueda";

      // Redirigir a la página con los parámetros de búsqueda
      window.location.href = redirectUrl;
    }

    if (button === "Actualizar") {
      // Objeto literal para los datos
      const data = {
        Selec: selects,
        Nombres: nombre,
        Descripciones: descripcion,
        Fotos: foto,
        Actualizar: "Actualizar",
      };
      // Convertir los datos a una cadena de consulta
      const params = new URLSearchParams(data);

      // URL donde se enviarán los datos
      const url = "/funkoplanet/views/categories_crud.php";

      // Agregar los parámetros de búsqueda a la URL de redirección
      const redirectUrl = url + "?" + params.toString();

      // Redirigir a la página con los parámetros de búsqueda
      window.location.href = redirectUrl;
    }
  };

  // Maneja el botón de incremento de cantidad de producto
  handleIncrement = () => {
    let cantidadSpan = document.getElementById("cantidad");
    let cantidad = parseFloat(cantidadSpan.innerText);
    cantidad++;
    cantidadSpan.innerHTML = cantidad;
  };

  // Maneja el botón de decremento de cantidad de producto
  handleDecrement = () => {
    let cantidadSpan = document.getElementById("cantidad");
    let cantidad = parseFloat(cantidadSpan.innerText);

    if (cantidad > 1) {
      cantidad--;
      cantidadSpan.innerHTML = cantidad;
    }
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
        console.error("Error:", error); // Manejar el error
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
}

export default StoreManagerController;
