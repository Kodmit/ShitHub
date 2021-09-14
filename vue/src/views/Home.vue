<template>
  <div class="home">
    <p class="text-h3 dly-cat-title">Tâches en cours</p>
    
    <v-progress-linear
      indeterminate
      color="yellow darken-2"
      v-if="loading"
    ></v-progress-linear>

    <v-row class="dly-overflow mt-2 mb-8">

      <v-col v-for="(user, index) in users" :key="index" sm="3">
        <user-card :user="user" />
      </v-col>

    </v-row>

    <p class="text-h3 dly-cat-title mb-0">Résumé de la semaine</p>
    <p class="text--secondary">Liste des tâches terminées durant la semaine en cours.</p>

    <sumary />

  </div>
</template>

<script>
import UserCard from "../components/UserCard.vue";
import axios from 'axios';
import Sumary from '../components/Sumary.vue';

export default {
  components: { UserCard, Sumary },
  name: "Home",
  data() {
    return {
      users: [],
      loading: true
    };
  },
  mounted() {
    axios.get("/users").then((response) => {
      this.users = response.data;
      this.loading = false;
    });

    axios.get('/tasks').then((response) => {
      this.$store.commit('tasks/setTasks', response.data);
    });
  },
};
</script>

<style scoped>
.dly-overflow {
  flex-wrap: nowrap;
  overflow: scroll;
}

.dly-cat-title {
  font-size: 1.40rem !important;
  line-height: 2rem;
  letter-spacing: 3px !important;
  font-family: "Roboto", sans-serif !important;
  text-transform: uppercase;
}

::-webkit-scrollbar {
  -webkit-appearance: none;
  width: 5px;
  height: 10px;
}
::-webkit-scrollbar-thumb {
  border-radius: 20px;
  background-color: #0091a2;
  -webkit-box-shadow: 0 0 1px rgba(255, 255, 255, .5);
}
</style>