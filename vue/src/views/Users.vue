<template>
  <div class="home">
    <h3>Liste des utilisateurs</h3>

    <v-simple-table>
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">
              Username
            </th>
            <th class="text-left">
              Création
            </th>
            <th class="text-left">
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.username">
            <td>{{ user.username }}</td>
            <td>{{ moment(user.createdAt).format('L') }}</td>
            <td>
              <router-link style="text-decoration: none" :to="{name: 'users-edit', params: {id: user.id}}">
                <v-btn icon color="primary">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </router-link>
              <v-btn icon color="primary" @click="deleteUser(user.id)">
                <v-icon>mdi-delete</v-icon>
              </v-btn>
            </td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>

  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  name: 'Users',
  data() {
    return {
      users: []
    }
  },
  mounted() {
    axios.get('/users').then((response) => {
      this.users = response.data;
    }).catch(() => {
      console.log('error');
    });
  },
  methods: {
    deleteUser(userId) {
      axios.delete('/users/' + userId)
      .then(() => {
        this.users = this.users.filter(
            user => user.id !== userId
        );
        Swal.fire('Utilisateur supprimé', '', 'success');
      })
    }
  }
}
</script>
