<template>
  <q-layout view="lHh lpR lFf">
    <q-header bordered class="bg-grey-8 text-white" height-hint="98">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="left = !left" />

        <q-toolbar-title>
          <q-avatar>
            <img
              src="https://cdn.quasar.dev/logo/svg/quasar-logo.svg"
            /> </q-avatar
          >Title
        </q-toolbar-title>
      </q-toolbar>

      <page-tab
        :tabs="tabs"
        :activeTab="activeTab"
        @switch="toggleTab"
      ></page-tab>
    </q-header>

    <q-drawer
      content-class="bg-grey-8 text-white"
      show-if-above
      v-model="left"
      side="left"
      behavior="desktop"
      bordered
    >
      <q-toolbar class="text-center">
        <q-toolbar-title>Laravel</q-toolbar-title>
      </q-toolbar>

      <left-menu
        v-if="tree"
        :tree="tree"
        :activeMenu="activeTab"
        @nav="toggleTab"
      ></left-menu>
    </q-drawer>

    <q-page-container :style="style">
      <div
        v-for="(tab, index) in tabs"
        :key="index"
        class="content"
        :class="{ show: activeTab && activeTab.id == tab.id }"
      >
        <iframe frameborder="0" :src="tab.url" class="iframe"></iframe>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script>
import LeftMenu from "./LeftMenu";
import PageTab from "./PageTab";
import { mapState, mapGetters, mapActions } from "vuex";
export default {
  components: {
    LeftMenu,
    PageTab,
  },
  data() {
    return {
      left: false,
      height: this.$q.screen.height,
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
    },
  },
  methods: {
    ...mapActions("menu", ["loadTree", "toggleTab"]),
  },
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
