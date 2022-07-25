<template>
  <div class="loader" v-if="loader">
    <img src="/img/loading.gif" alt="template-badge">
  </div>
</template>
<script>
export default {
  name: 'pic-snippet-loader',
  data() {
    return {
      loader: false,
    }
  },
  mounted() {
    Bus.$on('showLoading', (data) => {
      if (data) {
        this.loader = true;
      } else {
        this.loader = false;
      }
    });
  },
  methods: {},
  destroyed() {
    Bus.$off('showLoading');
  }
}
</script>
<style lang="scss" scoped>
.loader {
  position: absolute;
  width: 100%;
  height: 100vh;
  background-color: rgba(#F3FBFE, .5);
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
Footer
