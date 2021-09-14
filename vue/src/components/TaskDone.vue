<template>
  <v-card class="mx-auto mb-5" color="#0091a2" dark max-width="400">

    <v-progress-linear
      color="orange"
      indeterminate
      reverse
      v-if="loading"
    ></v-progress-linear>

    <v-card-text class="body-1" style="color: #fff">
      {{ task.description }}
    </v-card-text>

    <v-card-actions class="mt-0">
      <v-list-item class="grow">

        {{ moment(task.createdAt).fromNow() }}
        

        <v-row justify="end">
          
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <div v-on="on" v-bind="attrs">
                <v-switch v-model="toggle" @click="toggleDone()" color="orange" value="primary" hide-details></v-switch>
              </div>
            </template>

            <span>Marquer comme terminé</span>

          </v-tooltip>
        </v-row>

      </v-list-item>
    </v-card-actions>
  </v-card>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Task',
  props: {
      task: {
          type: Object,
          required: true
      }
  },
  data: () => ({
      show: false,
      loading: false,
      toggle: false

  }),
  methods: {
    toggleDone() {
      this.loading = true;
this.$store.commit('tasks/removeTask', this.task.id);
this.toggle = false;
      axios.patch('/tasks/' + this.task.id, { done: true })
      .then((response) => {
        this.$store.commit('tasks/removeTask', response.data.id);
        this.loading = false;
      })
      
    }
  }
}
</script>
