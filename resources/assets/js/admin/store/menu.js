import { merge } from "lodash";
import { mapStore } from "../utils/store";

export default merge(
  {
    namespaced: true,
    state: { tree: null, tabs: null, activeTab: null },
    mutations: {
      setTabs(state, tabs) {
        state.tabs = tabs;
      },
      setActiveTab(state, tab) {
        state.activeTab = tab;
      },
    },
    getters: {
      tabs(state) {
        return state.tabs || [];
      },
      activeTab(state) {
        return state.activeTab || {};
      },
    },
    actions: {
      toggleTab({ commit, getters }, tab) {
        if (!getters.tabs.find((getterTab) => getterTab.id == tab.id)) {
          commit("setTabs", [...getters.tabs, tab]);
        }
        commit("setActiveTab", tab);
      },
    },
  },
  mapStore("tree", {
    url: "/api/admin/v1/menu/tree",

    getter: false,
    loadingKey: "menu.tree",
    options: {
      actionOptions: {
        commitType: "data",
      },
    },
  })
);
