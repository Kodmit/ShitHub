<template>
  <div class="home">
    <h3>Modifier l'utilisateur {{ user.username }}</h3>

    <form @submit.prevent="updateUser()">
      <v-text-field
        v-model="form.username"
        label="Username"
        outlined
        clearable
        :loading="loading"
      ></v-text-field>

      <v-btn color="primary" type="submit" :loading="loading"> Modifier </v-btn>

    </form>

  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  name: 'UserEdit',
  data() {
    return {
      user: {},
      loading: true,
      form: {
        username: ''
      }
    }
  },
  mounted() {
    axios.get('/users/' + this.$route.params.id).then((response) => {
      this.user = response.data;
      this.form.username = this.user.username;
      this.loading = false;
    }).catch(() => {
      console.log('error');
    });
  },
  methods: {
    updateUser() {
      this.loading = true;

      axios.put('/users/' + this.$route.params.id, {username: this.form.username}).then((response) => {
        this.user = response.data;
        this.loading = false;
        Swal.fire('Utilisateur modifié !', '', 'success');
      })
    }
  }
}
</script>
