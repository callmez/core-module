import Vue from "vue";
import { mapGetters, mapActions } from "vuex";
import $G from "./global";
import handleError from "./errorHandler";

Vue.mixin({
  computed: {
    ...mapGetters({ hasLoading: "loading" }),

    // 全局配置
    $G: () => $G,
  },

  methods: {
    ...mapActions([
      "toggleLoading", // 切换loading状态
    ]),
    // 错误处理
    handleError,
  },
});
