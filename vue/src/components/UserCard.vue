<template>
  <v-card max-width="300" outlined>
    
    <v-card-actions>
      <v-btn color="orange lighten-2" text>
        <v-icon large left>
        mdi-account
      </v-icon>
      <span class="text-h6 font-weight-light">{{ user.username }}</span>
      </v-btn>

      <v-spacer></v-spacer>

      <v-btn icon @click="show = !show">
        <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-plus' }}</v-icon>
      </v-btn>
    </v-card-actions>
      
    <v-expand-transition>
      <div v-show="show">
        <v-divider></v-divider>

        <form @submit.prevent="createTask()">

          <v-textarea
            v-model="form.description"
            outlined
            :disabled="loading"
            rows="3"
            class="mr-2 ml-2 mt-2"
            name="input-7-4"
            label="Entrez une description"
            style="margin-bottom: -30px;"
          ></v-textarea>

          <v-checkbox
            class="ma-2"
            :disabled="loading"
            v-model="form.done"
            label="Marquer comme terminé"
          ></v-checkbox>

          <div class="text-right">
            <v-btn
              type="submit"
              :loading="loading"
              class="mr-2"
              style="margin-top: -25px"
              small
              depressed
              color="primary"
            >
              Ajouter
            </v-btn>
          </div>

        </form>
        <v-divider></v-divider>

      </div>
    </v-expand-transition>

    <v-card-text style="max-height: 600px; overflow: scroll">

      <task 
        v-for="task in tasks"
        :key="task.id"
        :task="task"
      />

    </v-card-text>
    <v-card-actions>
      <v-btn color="orange lighten-2" text>
        Historique
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import Task from './Task.vue'
import axios from 'axios';

export default {
components: { Task },
  name: 'UserCard',
  props: {
      user: {
        type: Object,
        required: true
      }
  },
  data: () => ({
      show: false,
      loading: false,
      form: {
        description: null,
        done: false
      }
  }),
  computed: {
    tasks: function() {
      return this.$store.state.tasks.all.filter(
        task => task.user.id === this.user.id
      );
    }
  },
  methods: {
    createTask() {
      this.loading = true;

      axios.post('/tasks', {
        userId: this.user.id,
        description: this.form.description,
        done: this.form.done
      })
      .then((response) => {
        this.$store.commit('tasks/addTask', response.data);
        this.form.description = null;
        this.$toastr.s("Tache ajoutée !");
        this.loading = false;
      })
      .catch(() => {
        this.loading = false;
      })
    },
    removeTask(taskId) {
      console.log(taskId);
    }
  }
}
</script>
