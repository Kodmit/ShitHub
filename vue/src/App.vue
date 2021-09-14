<template>
  <div id="app">
    <v-app>
      <router-view/>
    </v-app>
  </div>
</template>

<style lang="scss">
</style>

<script>
import auth from "./service/auth";

export default {
  mounted() {
    if (auth.isLogged() === false && this.$route.name !== 'login') {
      this.$router.push({name: 'login'});
    }
  }
}
</script>