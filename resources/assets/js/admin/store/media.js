import { merge } from "lodash";
import { mapStore } from "../utils/store";

export default merge(
  {
    namespaced: true,
    state: {
      types: [
        "ai",
        "apk",
        "bt",
        "cad",
        "code",
        "dir",
        "doc",
        "eps",
        "exe",
        "fla",
        "fonts",
        "ipa",
        "keynote",
        "links",
        "misc",
        "mm",
        "mmap",
        "audio",
        "mp4",
        "number",
        "pages",
        "pdf",
        "ppt",
        "ps",
        "rar",
        "document",
        "visio",
        "web",
        "xls",
        "xmind",
        "archive"
      ]
    },
    mutations: {},
    getters: {},
    actions: {}
  },
  mapStore("files", { url: "/api/admin/v1/media", loadingKey: "media.files" })
);
