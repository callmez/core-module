<template>
  <q-list class="left-menu-list" dark>
    <template v-for="menu in tree">
      <q-expansion-item
        :key="menu.id"
        v-if="menu.children"
        :icon="menu.icon"
        :label="menu.title"
        expand-separator
      >
        <template v-for="subMenu in menu.children">
          <q-expansion-item
            :key="subMenu.id"
            v-if="subMenu.children"
            :icon="subMenu.icon"
            :label="subMenu.title"
            default-opened
            :header-inset-level="0.3"
          >
            <q-item
              v-for="grandSubMenu in subMenu.children"
              :key="grandSubMenu.id"
              clickable
              v-ripple
              active-class="bg-secondary text-white"
              :active="isActive(grandSubMenu)"
              @click="handleNav(grandSubMenu)"
            >
              <q-item-section>
                <q-item-label style="padding-left: 84px">
                  {{ grandSubMenu.title }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-expansion-item>
          <q-item
            v-else
            :key="subMenu.id"
            clickable
            v-ripple
            :active="isActive(subMenu)"
            active-class="bg-secondary text-white"
            @click="handleNav(subMenu)"
            style="padding-left: 32.8px"
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
        active-class="bg-secondary text-white"
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

<style lang="scss" scoped>
.left-menu-list .q-expansion-item__content {
  background: rgba(0, 0, 0, 0.3);
}
</style>
