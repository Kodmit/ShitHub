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
              <v-btn icon color="primary">
                <v-icon>mdi-pencil</v-icon>
              </v-btn>
              <v-btn icon color="primary">
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
  }
}
</script>
