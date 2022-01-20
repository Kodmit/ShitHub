<template>
  <div>redirect uri g-auth</div>
</template>

<script>
export default {
  mounted() {
    this.storeTokenToLocalStorage();
    this.$router.push({name: 'todo'})
  },
  methods: {
    storeTokenToLocalStorage() {
      let rawAuthenticationParams = this.$route.hash;

      // Remove # character
      rawAuthenticationParams = rawAuthenticationParams.slice(
        1,
        rawAuthenticationParams.length - 1
      );

      // split params and store in local storage
      rawAuthenticationParams
        .split("&")
        .map((paramPairString) => {
          return paramPairString.split("=");
        })
        .forEach((pair) => {
          localStorage.setItem(pair[0], pair[1]);
        });
    },
  },
};
</script>

<style></style>
