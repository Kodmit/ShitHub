<template>
  <div>
    <h3 class="mb-4">Ajouter un utilisateur</h3>

    <v-form ref="form" @submit.prevent="submit">
      <v-text-field
        v-model="form.username"
        label="Username"
        outlined
        clearable
        :rules="[rules.required]"
        :loading="loading"
      ></v-text-field>

      <v-text-field
        v-model="form.password"
        :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
        :rules="[rules.required, rules.min]"
        :type="showPassword ? 'text' : 'password'"
        name="input-10-1"
        label="Entrez un mot de passe"
        hint="Le mot de passe doit faire au moins 8 caractères"
        counter
        outlined
        clearable
        @click:append="showPassword = !showPassword"
        :loading="loading"
      ></v-text-field>

      <v-text-field
        v-model="form.passwordRepeat"
        :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
        :rules="[rules.required, rules.samePassword]"
        :type="showPassword ? 'text' : 'password'"
        name="input-10-1"
        label="Confirmez votre mot de passe"
        outlined
        clearable
        @click:append="showPassword = !showPassword"
        :loading="loading"
      ></v-text-field>

      <v-btn color="primary" type="submit" :loading="loading"> Ajouter </v-btn>
    </v-form>
  </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
  name: "UserCreate",
  data() {
    return {
      loading: false,
      form: {
        username: "",
        password: "",
        passwordRepeat: "",
      },
      step: 1,
      showPassword: false,
      rules: {
        required: (value) => !!value || "Requis.",
        min: (value) => value.length >= 8 || "Minimum 8 caractères",
        samePassword: (value) =>
          value === this.form.password ||
          "Doit être identique au premier mot de passe"
      },
    };
  },
  methods: {
    submit() {
      this.loading = true;
      this.$refs.form.validate();

      axios
        .post("/users", {
          username: this.form.username,
          password: this.form.password,
        })
        .then(() => {
          this.loading = false;
          Swal.fire({
            icon: "success",
            title: "Utilisateur " + this.username + " créé",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Liste des utilisateurs`,
            denyButtonText: `Fermer`,
          }).then((result) => {
            if (result.isConfirmed) {
              this.$router.push({ name: "users" });
            }
          });
        })
        .catch(() => {
            this.loading = false;
          Swal.fire("Erreur", "L'utilisateur n'a pas pu être créé", "error");
        });
    },
  },
};
</script>
