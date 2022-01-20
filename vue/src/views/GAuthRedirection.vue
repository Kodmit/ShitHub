<template>
  <div>redirect uri g-auth</div>
</template>

<script>
import googleAuth from "@/service/googleAuth";
export default {
  mounted() {
    this.storeTokenToLocalStorage();
    this.$router.push({name: 'todo'})
  },
  methods: {
    storeTokenToLocalStorage() {
      const queryParams = this.$route.query;
      const code = queryParams.code;

      googleAuth.getTokenFromCode(code);
    },
  },
};
</script>

<style></style>
