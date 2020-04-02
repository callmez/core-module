import { merge } from "lodash";

const G = merge(
  {
    url: {
      base: window.location ? window.location.origin : "",
      media: {
        upload: "/api/admin/v1/media/upload"
      }
    }
  },
  window.G || {}
);

export default G;
