<template>
  <div>
  <v-btn @click="redirectToAuth()">S'authentifier avec google</v-btn>
  </div>
</template>

<script>
export default {
    methods: {
        redirectToAuth() {
            window.location.href = `${process.env.VUE_APP_AUTH_URL}?scope=https%3A//www.googleapis.com/auth/spreadsheets&include_granted_scopes=true&response_type=token&state=state_parameter_passthrough_value&redirect_uri=${process.env.VUE_APP_URL}/g-auth/redirect-uri&client_id=${process.env.VUE_APP_CLIENT_ID}`;
        }
    }
}
</script>

<style>

</style>