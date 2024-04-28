import StoreManager from "./storemanager.js";
import StoreManagerController from "./storemanagerController.js";
import StoreManagerView from "./storemanagerView.js";

const StoreManagerApp = new StoreManagerController(
  StoreManager.getInstance(),
  new StoreManagerView()
);

export default StoreManagerApp;
