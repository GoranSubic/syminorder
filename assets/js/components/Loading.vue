<template>
  <div class="flex-container-loading">
    <h4 class="component">

    </h4>
  </div>
</template>

<script>
export default {
  name: "Loading"
}
</script>

<style scoped>
.flex-container-loading {
  display: flex;
  justify-content: center;
}
.component {
  background: url('../../media/loader.gif') no-repeat center center;
  padding: 0 0 4px 5px;
  height: 100px;
  width: 500px;
}
</style>