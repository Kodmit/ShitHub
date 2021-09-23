<template>
<div>
     <v-app id="inspire">
    <v-app-bar app color="white" flat>
      <v-avatar :color="$vuetify.breakpoint.smAndDown ? 'grey darken-1' : 'transparent'" size="32"></v-avatar>

      <v-tabs centered class="ml-n9" color="grey darken-1">
        <v-tab :to="{name: 'home'}">Dashboard</v-tab>
        <v-tab :to="{name: 'users'}">Utilisateurs</v-tab>
        <v-tab :to="{name: 'reports'}">Rapports</v-tab>
      </v-tabs>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn depressed v-bind="attrs" v-on="on">
            {{ username }}
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title @click="logout()">Déconnexion</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main class="grey lighten-3">
      <v-container fluid>
        <v-row>
          <v-col cols="12" sm="2">
            <v-sheet class="text-center pa-5" rounded="lg" min-height="268">
              <p>{{ moment().format('LLLL') }}</p>

              <v-btn depressed class="mt-5" :to="{name: 'users-create'}">
                Créer un utilisateur
              </v-btn>
              
            </v-sheet>
          </v-col>

          <v-col cols="12" sm="10">
            <v-sheet min-height="70vh" rounded="lg" class="pa-5">
              <router-view />
            </v-sheet>
          </v-col>

        </v-row>
      </v-container>
    </v-main>
  </v-app>
</div>
</template>

<script>
import auth from '../service/auth';

export default {
  name: 'ConnectedLayout',
  data: () => ({
      username: null,
      links: [
        'Dashboard',
        'Messages',
        'Profile',
        'Updates',
      ],
    }),
    mounted() {
      this.username = auth.getToken().username;
    },
    methods: {
      logout() {
        auth.logout();
        this.$router.go(0);
      }
    }
}
</script>
