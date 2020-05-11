<template>
  <div>
    <q-tabs align="left" :value="activeTab.id" dense no-caps inline-label>
      <q-tab
        v-for="tab in tabs"
        :key="tab.id"
        :name="tab.id"
        :label="tab.title"
        @click.stop="switchTab(tab)"
        ><q-btn
          class="close"
          icon="close"
          size="xs"
          flat
          round
          dense
          @click.stop="removeTab(tab)"
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
    removeTab(tab) {
      this.$emit("remove", tab);
    },
  },
};
</script>

<style lang="scss" scoped>
.close {
  position: absolute;
  right: 5px;
  top: 9px;
}
</style>
