import { BaseException } from "./exceptions.js";

import { Category } from "./objects.js";

// Excepción que heredad de Base para crear excepciones propias del Manager de Restaurantes
class ManagerException extends BaseException {
  constructor(message = "Error: Manager Exception.", fileName, lineNumber) {
    super(message, fileName, lineNumber);
    this.name = "ManagerException";
  }
}

// Excepción que se da cuando un objeto no es una instancia de la solicitada
class ObjecManagerException extends ManagerException {
  constructor(param, className, fileName, lineNumber) {
    super(`Error: The ${param} is not a ${className}`, fileName, lineNumber);
    this.param = param;
    this.param = className;
    this.name = "ObjecManagerException";
  }
}

// Excepción que se da cuando una categoría ya existe
class ObjectExistsException extends ManagerException {
  constructor(object, fileName, lineNumber) {
    super(
      `Error: The ${object.name} already exists in the manager.`,
      fileName,
      lineNumber
    );
    this.object = object;
    this.name = "ObjectExistsException";
  }
}

// Excepción que se da cuando una objeto no existe
class ObjectNotExistException extends ManagerException {
  constructor(object, fileName, lineNumber) {
    super(
      `Error: The ${object.name} doesn't exist in the manager.`,
      fileName,
      lineNumber
    );
    this.object = object;
    this.name = "objectNotExistException";
  }
}

// Patrón Singleton para el objeto de manager de restaurantes
const StoreManager = (function () {
  let instantiated;

  class StoreManager {
    // Propiedades privadas
    #categories = [];
    #name = "";

    #objectsConstructors = {
      Category,
    };

    // Función interna que permite obtener la posición de una categoría
    #getCategoryPosition(category) {
      return this.#categories.findIndex(
        (x) => x.category.name === category.name
      );
    }

    // Inicio de constructor, el nombre será, por defecto, el nombre
    constructor(name = "Manager de Tienda") {
      this.#name = name;

      // Getter de categories
      Object.defineProperty(this, "categories", {
        enumerable: true,
        get() {
          const array = this.#categories;
          return {
            *[Symbol.iterator]() {
              for (const category of array) {
                yield category;
              }
            },
          };
        },
      });
    }

    // Permite añadir una o más categorías siempre y cuando sean una instancia de Category
    addCategory(...categories) {
      for (const category of categories) {
        if (!(category instanceof Category)) {
          throw new ObjecManagerException("category", "Category");
        }
        const position = this.#getCategoryPosition(category);
        if (position === -1) {
          this.#categories.push({
            category,
          });
          // Permite ordenar las categorías alfabéticamente
          // this.#categories.sort(this.#sortCategoriesFunc);
        } else {
          throw new ObjectExistsException(category);
        }
      }
      return this;
    }

    // Permite eliminar una o más categorías, siempre que sea una instancia de Category y esté registrada
    removeCategory(...categories) {
      for (const category of categories) {
        if (!(category instanceof Category)) {
          throw new ObjecManagerException("category", "Category");
        }
        const position = this.#getCategoryPosition(category);
        if (position !== -1) {
          this.#categories.splice(position, 1);
        } else {
          throw new ObjectNotExistException(category);
        }
      }
      return this;
    }

    // Función que permite crear una categoría siempre y cuando no exista. Si la categoría existe,
    // Devuelve la instancia de esa categoría.
    createCategory(name, type) {
      let category = this.#categories.find(
        (element) => element.category.name === name
      );

      if (!category) {
        category = new this.#objectsConstructors[type](name);
      } else {
        category = category.category;
      }
      return category;
    }
  }

  function init() {
    const storeManager = new StoreManager();
    Object.freeze(storeManager);
    return storeManager;
  }

  return {
    getInstance() {
      if (!instantiated) {
        instantiated = init();
      }
      return instantiated;
    },
    Category: Category.name,
  };
})();

export default StoreManager;
export { Category } from "./objects.js";
