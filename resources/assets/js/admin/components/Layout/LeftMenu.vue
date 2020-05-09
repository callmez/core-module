<template>
  <q-list dark>
    <template v-for="menu in tree">
      <q-expansion-item
        :key="menu.id"
        v-if="menu.children"
        :icon="menu.icon"
        :label="menu.title"
        expand-separator
        default-opened
        :content-inset-level="0.5"
      >
        <template v-for="subMenu in menu.children">
          <q-expansion-item
            :key="subMenu.id"
            v-if="subMenu.children"
            :icon="subMenu.icon"
            :label="subMenu.title"
            default-opened
            :content-inset-level="1"
          >
            <q-item
              v-for="grandSubMenu in subMenu.children"
              :key="grandSubMenu.id"
              clickable
              v-ripple
              :active="isActive(grandSubMenu)"
              @click="handleNav(grandSubMenu)"
            >
              <q-item-section>
                <q-item-label>{{ grandSubMenu.title }}</q-item-label>
              </q-item-section>
            </q-item>
          </q-expansion-item>
          <q-item
            v-else
            :key="subMenu.id"
            clickable
            v-ripple
            :active="isActive(subMenu)"
            @click="handleNav(subMenu)"
          >
            <q-item-section avatar>
              <q-icon :name="subMenu.icon" />
            </q-item-section>

            <q-item-section>{{ subMenu.title }}</q-item-section>
          </q-item>
        </template>
      </q-expansion-item>

      <q-item
        v-else
        :key="menu.id"
        clickable
        v-ripple
        :active="isActive(menu)"
        @click="handleNav(menu)"
      >
        <q-item-section avatar>
          <q-icon :name="menu.icon" />
        </q-item-section>

        <q-item-section>{{ menu.title }}</q-item-section>
      </q-item>
    </template>
  </q-list>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  props: {
    tree: {
      type: Array,
      required: true,
    },
    activeMenu: {
      type: Object,
      default: {},
    },
  },

  methods: {
    handleNav(menu) {
      this.$emit("nav", menu);
    },
    isActive(menu) {
      return this.activeMenu.id == menu.id;
    },
  },
};
</script>
