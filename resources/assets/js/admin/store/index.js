import Vue from "vue";
import Vuex from "vuex";
import media from "./media";

// import example from './module-example'

Vue.use(Vuex);

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default function(/* { ssrContext } */) {
  const Store = new Vuex.Store({
    state: {
      loading: {}
    },
    mutations: {
      setLoading(state, loading) {
        state.loading = loading;
      }
    },
    actions: {
      toggleLoading({ state, commit }, { key, loading }) {
        commit("setLoading", {
          ...state.loading,
          [key]: typeof loading === "boolean" ? loading : !state.loading[key]
        });
      }
    },
    modules: {
      media
      // example
    },

    // enable strict mode (adds overhead!)
    // for dev mode only
    strict: process.env.DEV
  });

  return Store;
}
