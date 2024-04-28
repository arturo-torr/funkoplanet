import { EmptyValueException, InvalidValueException } from "./exceptions.js";

// Objeto con la que será creada la estructura de categorías
class Category {
  #name;
  #description;

  // En el constructor sólo será obligatorio el nombre de la categoría
  constructor(name = " ") {
    name = name.trim();

    // Si no introducimos un valor, lanza una excepción
    if (name === "undefined" || name === "")
      throw new EmptyValueException("name");

    this.#name = name;
    this.#description = null;
  }

  // --- Getters & Setters ---
  get name() {
    return this.#name;
  }

  set name(value = "EmptyCategory") {
    value = value.trim();
    if (value === "undefined" || value === "EmptyCategory" || value === "")
      throw new EmptyValueException("value");

    this.#name = value;
  }

  get description() {
    return this.#description;
  }

  set description(value) {
    if (value === "undefined" || value == null)
      throw new EmptyValueException("description");

    this.#description = value;
  }

  // Imprime por pantalla las propiedades del objeto Category
  toString() {
    return (
      "Category name: " + this.#name + ", Description: " + this.#description
    );
  }

  toJSON() {
    return {
      name: this.#name,
      description: this.#description,
    };
  }
}

export { Category };
