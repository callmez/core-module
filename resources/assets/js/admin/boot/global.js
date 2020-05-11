import { merge } from "lodash";

const G = merge(
  {
    name: "Laravel",
    url: {
      base: window.location ? window.location.origin : "",
      media: {
        upload: "/api/admin/v1/media/upload",
      },
      auth: {
        logout: "/admin/auth/logout",
      },
      menu: {
        tree: "/api/admin/v1/menu/tree",
      },
    },
  },
  window.G || {}
);

export default G;
