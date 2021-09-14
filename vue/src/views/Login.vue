<template>
<div>
    <v-card
      class="d-flex justify-space-around pa-12"
      flat
      tile
    >
      <v-card class="pa-6" elevation="7" width="40%">

        <h3 class="mb-4">Connectez-vous</h3>

        <v-alert
          dense
          outlined
          type="error"
          v-if="error"
        >
          Vos identifiants sont incorrects
        </v-alert>

        <form @submit.prevent="login()">

          <v-text-field
              v-model="form.username"
              label="Username"
              outlined
              clearable
              :loading="loading"
              :error="error"
            ></v-text-field>

            <v-text-field
              v-model="form.password"
              :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              :rules="[rules.required]"
              :type="showPassword ? 'text' : 'password'"
              name="input-10-1"
              label="Entrez un mot de passe"
              outlined
              clearable
              @click:append="showPassword = !showPassword"
              :loading="loading"
              :error="error"
            ></v-text-field>

            <v-btn elevation="2" class="mt-3" style="display: block" type="submit" :loading="loading">Connexion</v-btn>

        </form>

      </v-card>

    </v-card>
</div>
</template>

<script>
import axios from 'axios';
import auth from '../service/auth';

export default {
  name: 'Login',
  data() {
      return {
          loading: false,
          error: false,
          form: {
            username: '',
            password: ''
          },
          showPassword: false,
          rules: {
            required: value => !!value || 'Requis.'
        }
      }
  },
  methods: {
    login() {
      this.loading = true;

      axios.post('/login_check', {
        username: this.form.username,
        password: this.form.password
      }).then((response) => {
        auth.login(response.data.token);
        this.$router.push({name: 'home'});
      }).catch(() => {
        this.loading = false;
        this.error = true;
      });
    }
  }
}
</script>
