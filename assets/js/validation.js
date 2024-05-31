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

  if (form) {
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
        handler();
      }

      event.preventDefault();
      event.stopPropagation();
    });
  }
}

function validacionRegistro(handler) {
  const form = document.forms.fRegistro;

  if (form) {
    form.setAttribute("novalidate", true);
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      event.stopPropagation();
      let isValid = true;
      let firstInvalidElement = null;

      this.username.value = this.username.value.trim();
      this.email.value = this.email.value.trim();
      this.password.value = this.password.value.trim();
      this.password2.value = this.password2.value.trim();

      if (!this.password.checkValidity()) {
        isValid = false;
        showFeedBack(this.password, false);
        firstInvalidElement = this.password;
      } else if (this.password.value !== this.password2.value) {
        isValid = false;
        showFeedBack(
          this.password,
          false,
          "La contraseña y la confirmación deben ser iguales."
        );
        showFeedBack(this.password2, false);
        firstInvalidElement = this.password;
      } else {
        showFeedBack(this.password, true);
        showFeedBack(this.password2, true, " ");
      }

      fetch("../web/comprobaremailexistente.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          email: this.email.value,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showFeedBack(this.email, true);
          } else {
            isValid = false;
            showFeedBack(this.email, false);
            firstInvalidElement = this.email;
          }
        });

      fetch("../web/comprobarusuarioexistente.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          username: this.username.value,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showFeedBack(this.username, true);
          } else {
            isValid = false;
            showFeedBack(this.username, false);
            firstInvalidElement = this.username;
          }
        })
        .catch((error) => console.error("Error:", error));

      if (isValid) {
        handler(this.username.value, this.email.value, this.password.value);
      }

      event.preventDefault();
      event.stopPropagation();
    });
  }
}
export { validacionPago, validacionRegistro };
