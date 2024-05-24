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

function validacionPago(handler) {
  const form = document.forms.fPago;

  form.setAttribute("novalidate", true);
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    event.stopPropagation();
    let isValid = true;
    let firstInvalidElement = null;

    this.cvc.value = this.cvc.value.trim();
    if (!this.cvc.checkValidity()) {
      isValid = false;
      showFeedBack(this.cvc, false);
      firstInvalidElement = this.cvc;
    } else {
      showFeedBack(this.cvc, true);
    }

    this.cardDate.value = this.cardDate.value.trim();
    if (!this.cardDate.checkValidity()) {
      isValid = false;
      showFeedBack(this.cardDate, false);
      firstInvalidElement = this.cardDate;
    } else {
      showFeedBack(this.cardDate, true);
    }

    this.creditCard.value = this.creditCard.value.trim();
    if (!this.creditCard.checkValidity()) {
      isValid = false;
      showFeedBack(this.creditCard, false);
      firstInvalidElement = this.creditCard;
    } else {
      showFeedBack(this.creditCard, true);
    }

    this.titular.value = this.titular.value.trim();
    if (!this.titular.checkValidity()) {
      isValid = false;
      showFeedBack(this.titular, false);
      firstInvalidElement = this.titular;
    } else {
      showFeedBack(this.titular, true);
    }

    if (!isValid) {
      firstInvalidElement.focus();
    } else {
      console.log("valido");
    }

    event.preventDefault();
    event.stopPropagation();
  });
}

// function categoriesCrudValidation(handler) {
//   const form = document.forms.fCategorias;
//   if (form) {
//     form.setAttribute("novalidate", true);
//     form.addEventListener("submit", function (event) {
//       let isValid = true;
//       let firstInvalidElement = null;
//       const clickedButton = event.submitter;

//       let checkboxes = this.querySelectorAll('input[type="checkbox"]');
//       // Recorrer los checkboxes para encontrar los seleccionados
//       let seleccionados = [];
//       checkboxes.forEach(function (checkbox) {
//         if (checkbox.checked) {
//           seleccionados.push(checkbox);
//         }
//       });
//       // Por ejemplo, comprobar si se ha seleccionado al menos uno
//       if (seleccionados.length === 0) {
//         alert("Debes seleccionar al menos una categoría.");
//         isValid = false; // Esto evitará que el formulario se envíe
//       }

//       // SI SE HA PULSADO EN BUSCAR
//       if (clickedButton.name === "Buscar") {
//         if (!isValid) {
//           firstInvalidElement.focus();
//         } else {
//           // Manda al controlador los datos necesarios para la búsqueda, que es el nombre
//           handler(clickedButton.name, "", this.nombreNuevo.value);
//         }
//       }
//       event.preventDefault();
//       event.stopPropagation();
//     });
//     form.addEventListener("reset", function (event) {
//       for (const div of this.querySelectorAll(
//         "div.valid-feedback, div.invalid-feedback"
//       )) {
//         div.classList.remove("d-block");
//         div.classList.add("d-none");
//       }
//       for (const input of this.querySelectorAll("input")) {
//         input.classList.remove("is-valid");
//         input.classList.remove("is-invalid");
//       }
//       this.nombreNuevo.focus();
//     });
//     form.nombreNuevo.addEventListener("change", defaultCheckElement);
//   }
// }

// export { categoriesCrudValidation };
export { validacionPago };
