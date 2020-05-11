<template>
  <v-app>
    <v-app-bar id="app-bar" absolute app color="transparent" flat height="75">
      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
      <template v-slot:extension>
        <page-tab :tabs="tabs" :activeTab="activeTab" @switch="toggleTab"></page-tab>
      </template>
    </v-app-bar>
    <v-navigation-drawer
      id="core-navigation-drawer"
      :right="$vuetify.rtl"
      mobile-break-point="960"
      app
      width="260"
      v-bind="$attrs"
    >
      <template v-slot:img="props">
        <v-img :gradient="`to bottom`" v-bind="props" />
      </template>

      <v-divider class="mb-1" />

      <left-menu v-if="tree" :tree="tree" :activeMenu="activeTab" @nav="toggleTab"></left-menu>
    </v-navigation-drawer>
  </v-app>
</template>

<script>
import LeftMenu from "./LeftMenu";
import PageTab from "./PageTab";
import { mapState, mapGetters, mapActions } from "vuex";
export default {
  components: {
    LeftMenu,
    PageTab
  },
  data() {
    return {
      left: false
    };
  },
  created() {
    this.loadTree();
  },
  computed: {
    ...mapState("menu", ["tree"]),
    ...mapGetters("menu", ["tabs", "activeTab"]),
    style() {
      return { height: this.$q.screen.height + "px" };
    }
  },
  methods: {
    ...mapActions("menu", ["loadTree", "toggleTab"])
  }
};
</script>

<style lang="scss" scoped>
.content {
  position: relative;
  height: 100%;
  width: 100%;
  display: none;

  &.show {
    display: block;
  }

  .iframe {
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
  }
}
</style>
