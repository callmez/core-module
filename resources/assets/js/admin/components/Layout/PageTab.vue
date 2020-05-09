<template>
  <div>
    <q-tabs align="left" :value="activeTab.id" dense no-caps inline-label>
      <q-tab
        v-for="tab in tabs"
        :key="tab.id"
        :name="tab.id"
        :label="tab.title"
        @click.prevent="switchTab(tab)"
        ><q-btn icon="close" size="xs" flat round dense
      /></q-tab>
    </q-tabs>
  </div>
</template>

<script>
export default {
  props: {
    tabs: {
      type: Array,
      default: [],
    },
    activeTab: {
      type: Object,
      default: {},
    },
  },
  methods: {
    switchTab(tab) {
      this.$emit("switch", tab);
    },
  },
};
</script>

<style lang="sass" scoped></style>
