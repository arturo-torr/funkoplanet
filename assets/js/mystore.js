import StoreManagerApp from "./storemanagerApp.js";

// Refactorización en objeto literal para ser invocado en base al nombre de la acción
const historyActions = {
  init: () => {
    StoreManagerApp.handleInit();
  },
};

// Se define 'popstate' para restaurar el estado de la página en función del tipo de acción apilada
window.addEventListener("popstate", (event) => {
  if (event.state) {
    historyActions[event.state.action](event);
  }
});

history.replaceState({ action: "init" }, null);
