//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//UserRoutes
import UserRoutes from "../Management/UserManagement/User/setup/routes.js";
//routesimport EcommerceOrderRoutes from '../Management/EcommerceOrder/setup/routes.js';

import ContactRoutes from "../Management/Contact/setup/routes.js";
import UserRoleRoutes from "../Management/UserManagement/Role/setup/routes.js";
import FreeQuoteRoutes from "../Management/FreeQuote/setup/routes.js";

const routes = {
  path: "",
  component: Layout,
  children: [
    {
      path: "dashboard",
      component: Dashboard,
      name: "adminDashboard",
    },
    //management routes        EcommerceOrderRoutes,


    ContactRoutes,

    FreeQuoteRoutes,

    //user routes
    UserRoutes,
    UserRoleRoutes,
    //settings
    SettingsRoutes,
  ],
};

export default routes;
