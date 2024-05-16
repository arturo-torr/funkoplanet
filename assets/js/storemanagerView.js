import { categoriesCrudValidation } from "./validation.js";

const EXECUTE_HANDLER = Symbol("executeHandler");

class StoreManagerView {
  constructor() {
    this.initzone = document.getElementById("init_zone");
    this.centralzone = document.getElementById("central_zone");
    this.nuevosProductos = document.getElementById("nuevos_productos");
    this.menu = document.querySelector(".navbar");
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
        `<li class="hover-menu"><a data-category="${event.name}" class="dropdown-item fw-bold" href="#event-list">${event.name}</a></li>`
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
          class="nav-link dropdown-toggle text-white fw-bold no_decoration"
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

  // Función que permite mostrar en el menú de navegación un ítem dropdown con las categorías
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

  // // Manejadores que requieren de la validación del formulario
  bindCategoriesCrud(handler) {
    categoriesCrudValidation(handler);
  }
}

export default StoreManagerView;
