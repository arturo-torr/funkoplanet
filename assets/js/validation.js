// Muestra el feedback al usuario por si se ha equivocado al introducir algún dato o no es válido
function showFeedBack(input, valid, message) {
  const validClass = valid ? "is-valid" : "is-invalid";
  const messageDiv = valid
    ? input.parentElement.querySelector("div.valid-feedback")
    : input.parentElement.querySelector("div.invalid-feedback");
  for (const div of input.parentElement.getElementsByTagName("div")) {
    div.classList.remove("d-block");
  }
  messageDiv.classList.remove("d-none");
  messageDiv.classList.add("d-block");
  input.classList.remove("is-valid");
  input.classList.remove("is-invalid");
  input.classList.add(validClass);
  if (message) {
    messageDiv.innerHTML = message;
  }
}

function defaultCheckElement(event) {
  this.value = this.value.trim();
  if (!this.checkValidity()) {
    showFeedBack(this, false);
  } else {
    showFeedBack(this, true);
  }
}

function categoriesCrudValidation(handler) {
  const form = document.forms.fCategorias;

  if (form) {
    form.setAttribute("novalidate", true);
    form.addEventListener("submit", function (event) {
      let isValid = true;
      let firstInvalidElement = null;

      const clickedButton = event.submitter;

      // SI SE HA PULSADO EN ACTUALIZAR
      if (clickedButton.name == "Actualizar") {
        event.preventDefault();
        event.stopPropagation();
        let checkboxes = this.querySelectorAll('input[type="checkbox"]');
        let nombres = this.querySelectorAll(
          'input[type="text"][name^="Nombres"]'
        );
        let descripciones = this.querySelectorAll(
          'input[type="text"][name^="Descripciones"]'
        );
        let fotos = this.querySelectorAll('input[type="text"][name^="Fotos"]');
        let arrSelects = [];
        let arrNombres = [];
        let arrDescripciones = [];
        let arrFotos = [];

        // Construir el array de nombres
        nombres.forEach(function (input) {
          let data = {};
          data[input.id] = input.value;
          arrNombres.push(data);
        });

        // Construir el array de descripciones
        descripciones.forEach(function (input) {
          let data = {};
          data[input.id] = input.value;
          arrDescripciones.push(data);
        });

        // Construir el array de fotos
        fotos.forEach(function (input) {
          let data = {};
          data[input.id] = input.value;
          arrFotos.push(data);
        });

        // Recorremos todos los checkboxes
        checkboxes.forEach(function (checkbox) {
          // Si el checkbox está seleccionado, recuperamos el nombre correspondiente
          if (checkbox.checked) {
            arrSelects.push(checkbox.id);
            let idCategoria = checkbox.id;
            let nombre = document.querySelector(
              `input[name='Nombres[${idCategoria}]']`
            );

            if (nombre.value === "") {
              isValid = false;
              showFeedBack(nombre, false);
              firstInvalidElement = nombre;
            } else {
              showFeedBack(nombre, true);
            }

            if (!isValid) {
              firstInvalidElement.focus();
            }
          }
        });

        // Recorrer los checkboxes para encontrar los seleccionados
        let seleccionados = [];
        checkboxes.forEach(function (checkbox) {
          if (checkbox.checked) {
            seleccionados.push(checkbox);
          }
        });

        // Por ejemplo, comprobar si se ha seleccionado al menos uno
        if (seleccionados.length === 0) {
          alert("Debes seleccionar al menos una categoría.");
          isValid = false; // Esto evitará que el formulario se envíe
        }

        if (!isValid) {
          firstInvalidElement.focus();
        } else {
          // Manda al controlador los datos necesarios para la búsqueda, que es el nombre
          handler(
            clickedButton.name,
            arrSelects,
            arrNombres,
            arrDescripciones,
            arrFotos
          );
        }
      }

      // SI SE HA PULSADO EN BUSCAR
      if (clickedButton.name === "Buscar") {
        if (!isValid) {
          firstInvalidElement.focus();
        } else {
          // Manda al controlador los datos necesarios para la búsqueda, que es el nombre
          handler(clickedButton.name, "", this.nombreNuevo.value);
        }
      }

      event.preventDefault();
      event.stopPropagation();
    });
    form.addEventListener("reset", function (event) {
      for (const div of this.querySelectorAll(
        "div.valid-feedback, div.invalid-feedback"
      )) {
        div.classList.remove("d-block");
        div.classList.add("d-none");
      }
      for (const input of this.querySelectorAll("input")) {
        input.classList.remove("is-valid");
        input.classList.remove("is-invalid");
      }
      this.nombreNuevo.focus();
    });

    form.nombreNuevo.addEventListener("change", defaultCheckElement);
  }
}

export { categoriesCrudValidation };
