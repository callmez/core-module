/**
 * 通过mapStore脚手架方法来快速生成一个标准的store.state操作流程函数
 *
 * 返回结构:
 * ```
 * mapStore('files', { api: '/test'})
 * {
 *   state: {
 *      files: null,
 *   },
 *   mutations: {
 *      setFiles(state, files) {
 *        state.files = files
 *      }
 *   },
 *   getters: {
 *      files(state) {
 *        return state.files || defaultPaginationData;
 *      }
 *   },
 *   actions: {
 *      loadFiles() {
 *          ...
 *      }
 *   }
 * }
 * ```
 */
import { upperFirst, isEqual } from "lodash";
import $http from "../boot/http";
import { defaultPaginationData } from "./index";

function resolveMutation(name) {
  return (state, data) => (state[name] = data);
}

function resolveGetter(name, { options }) {
  return (state) => state[name] || options.defaultData;
}

function resolveAction(
  name,
  {
    url,
    method,
    loadingKey,
    stateKey,
    stateDefaultValue,
    getterKey,
    mutationKey,
    options,
  }
) {
  const isPageType = ["infinite", "page"].includes(options.commitType);
  const defaultParams = isPageType ? { page: 1, limit: 20 } : {};
  return async (
    { getters, dispatch, commit, state, rootState },
    params = {}
  ) => {
    const actionOptions = params.options || {};
    delete params.options;

    // state缓存判断,
    if (options.cacheState) {
      // 缓存数据(非强制更新)直接返回
      if (
        !isEqual(state[stateKey], stateDefaultValue) &&
        !actionOptions.force
      ) {
        return state[stateKey];
      }
    }

    // 请求方法
    const request =
      typeof options.request === "function"
        ? options.request
        : async () => {
            const { data } = await $http.request({
              url,
              method,
              params: method == "get" ? { ...defaultParams, ...params } : {},
              data: method != "get" ? params : {},
            });
            return data;
          };
    const data = await dispatch(
      "toggleLoading",
      {
        key: loadingKey,
        loading: request,
      },
      { root: true }
    );

    let commitData = {};
    if (options.commitType == "infinite") {
      // 无限拉取
      const oldData = getters[getterKey];
      commitData = {
        ...data,
        data: (data.current_page != 1 ? oldData.data : []).concat(data.data),
        has_next_page: data.last_page > data.current_page,
      };
    } else if (options.commitType == "page") {
      // 分页展示
      commitData = {
        ...data,
        has_next_page: data.last_page > data.current_page,
      };
    } else if (options.commitType == "data") {
      // 数据保存
      commitData = data;
    } else if (typeof options.commitType == "function") {
      // 自定义处理数据
      commitData = await options.commitType({ state, rootState }, data);
    }
    commit(mutationKey, commitData);

    return data;
  };
}

export function mapStore(
  name,
  {
    url,
    method = "get",
    options = {},
    loadingKey = name,
    stateKey = name,
    stateDefaultValue = null,
    mutationKey = "set" + upperFirst(stateKey),
    getterKey = name,
    actionKey = "load" + upperFirst(stateKey),
    mutation = resolveMutation(name),
    getter = resolveGetter(name, {
      options: { defaultData: defaultPaginationData, ...options.getterOptions },
    }),
    action = resolveAction(name, {
      url,
      method,
      loadingKey,
      stateKey,
      stateDefaultValue,
      getterKey,
      mutationKey,
      options: {
        commitType: "page",
        cacheState: false,
        ...options.actionOptions,
      },
    }),
  } = {}
) {
  const store = {
    state: {
      [stateKey]: stateDefaultValue,
    },
    mutations: {},
    getters: {},
    actions: {},
  };

  if (mutation !== false) {
    store.mutations[mutationKey] = mutation;
  }

  if (getter !== false) {
    store.getters[getterKey] = getter;
  }

  if (action !== false) {
    store.actions[actionKey] = action;
  }

  return store;
}
