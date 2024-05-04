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
    this.onAddCategory();
    this.onAddEvent();
    this[VIEW].showAdminMenu();
    // this[VIEW].bindAdminMenu(this.handleNewCategoryForm);
  };

  // Función que se ejecuta al cargar la página
  onInit = () => {
    this.handleNuevosProductos().catch((error) => {
      console.error("Error:", error);
    });
    this.handleCategoriesInCentralZone().catch((error) => {
      console.error("Error:", error);
    });
  };

  // Manejador de inicio
  handleInit = () => {
    this.onInit();
  };

  // Mostrado de categorías en navegación
  onAddCategory = () => {
    this.handleCategoriesInMenu()
      .then((categorias) => {
        this[VIEW].showCategoriesInMenu(categorias);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
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

  // Manejador para los últimos productos subidos en la página
  handleNuevosProductos = () => {
    if (this[VIEW].nuevosProductos) {
      return new Promise((resolve, reject) => {
        fetch("/funkoplanet/web/controlador_productos.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "parametro=nuevosProductos",
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("Error al obtener los productos");
            }
            return response.text();
          })
          .then((html) => {
            this[VIEW].nuevosProductos.innerHTML = html;
            this[VIEW].changeImagesInNewProducts();
            resolve();
          })
          .catch((error) => {
            reject(error);
          });
      });
    } else {
      return Promise.resolve(); // Devuelve una promesa resuelta si centralzone no está definido
    }
  };

  // Manejador que devuelve una promesa que permite cargar las categorías en la zona central de la página
  handleCategoriesInCentralZone = () => {
    if (this[VIEW].centralzone) {
      return new Promise((resolve, reject) => {
        fetch("/funkoplanet/web/controlador_categorias.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "parametro=categoriesCentral",
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("Error al obtener las categorías");
            }
            return response.text();
          })
          .then((html) => {
            this[VIEW].centralzone.innerHTML = html;
            resolve();
          })
          .catch((error) => {
            reject(error);
          });
      });
    } else {
      return Promise.resolve(); // Devuelve una promesa resuelta si centralzone no está definido
    }
  };

  // Manejador que devuelve una promesa permitiendo cargar las categorías en la barra de navegación
  handleCategoriesInMenu = () => {
    return new Promise((resolve, reject) => {
      fetch("/funkoplanet/web/controlador_categorias.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `parametro=${"categoriesMenu"}`,
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
