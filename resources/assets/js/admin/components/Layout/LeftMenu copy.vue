<template>
  <v-list>
    <template v-for="(menu) in tree">
      <v-list-group
        :value="activeMenu.id"
        v-if="menu.children"
        :key="menu.id"
        :prepend-icon="menu.icon"
      >
        <template v-slot:activator>
          <v-list-item-title>{{menu.title}}</v-list-item-title>
        </template>

        <template v-for="(subMenu) in menu.children">
          <v-list-group v-if="subMenu.children" :key="subMenu.id" no-action sub-group>
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title>{{subMenu.title}}</v-list-item-title>
              </v-list-item-content>
            </template>

            <v-list-item
              v-for="(grandSubMenu) in subMenu.children"
              :key="grandSubMenu.id"
              link
              @click="handleNav(grandSubMenu)"
            >
              <v-list-item-title v-text="grandSubMenu.title"></v-list-item-title>
              <v-list-item-icon v-if="grandSubMenu.icon">
                <v-icon v-text="grandSubMenu.icon"></v-icon>
              </v-list-item-icon>
            </v-list-item>
          </v-list-group>

          <v-list-item v-else :key="subMenu.id" link @click="handleNav(subMenu)">
            <v-list-item-icon></v-list-item-icon>
            <v-list-item-title>{{subMenu.title}}</v-list-item-title>

            <v-list-item-icon>
              <v-icon v-text="subMenu.icon"></v-icon>
            </v-list-item-icon>
          </v-list-item>
        </template>
      </v-list-group>

      <v-list-item v-else :key="menu.id" link @click="handleNav(menu)">
        <v-list-item-icon>
          <v-icon v-text="menu.icon"></v-icon>
        </v-list-item-icon>

        <v-list-item-title>{{menu.title}}</v-list-item-title>
      </v-list-item>
    </template>
  </v-list>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  props: {
    tree: {
      type: Array,
      required: true
    },
    activeMenu: {
      type: Object,
      default: {}
    }
  },
  data: () => ({
    admins: [
      ["Management", "people_outline"],
      ["Settings", "settings"]
    ],
    cruds: [
      ["Create", "add"],
      ["Read", "insert_drive_file"],
      ["Update", "update"],
      ["Delete", "delete"]
    ]
  }),
  methods: {
    handleNav(menu) {
      this.$emit("nav", menu);
    }
  }
};
</script>
