<template>
  <div>redirect uri g-auth</div>
</template>

<script>
import github from "@/service/github";
export default {
  mounted() {
    this.storeTokenToLocalStorage();
    this.$router.push({ name: "todo" });
  },
  methods: {
    storeTokenToLocalStorage() {
      const queryParams = this.$route.query;
      const code = queryParams.code;

      github.getTokenFromCode(code);
    },
  },
};
</script>

<style></style>
