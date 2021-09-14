<template>
  <v-card class="mx-auto mb-5" color="#0091a2" dark max-width="400">

    <v-progress-linear
      color="orange"
      indeterminate
      reverse
      v-if="loading"
    ></v-progress-linear>

    <v-card-text class="body-1" style="color: #fff">
      <span style="color: orange; display: block; font-weight: bold" class="mb-1">
        <v-icon style="color: orange">mdi-account</v-icon> {{ task.user.username }}
      </span>
      {{ task.description }}
    </v-card-text>

    <v-card-text class="pt-0">
      Créé <b>{{ moment(task.createdAt).calendar() }}</b><br>
      Durée : <b>{{ dayDiff }} jour(s)</b>
    </v-card-text>

    <div class="d-flex justify-space-between">

      <v-chip class="mt-3 ml-3">Terminée à {{ moment(task.updatedAt).format('H:mm') }}</v-chip>

      <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <div v-on="on" v-bind="attrs">
              <v-switch v-model="isDone" @click="toggleDone()" color="orange" class="mb-3 mr-3" hide-details></v-switch>
            </div>
          </template>
          <span>Marquer comme non-terminé</span>
        </v-tooltip>

    </div>
  </v-card>
</template>

<script>
import axios from 'axios';

export default {
  name: 'SumaryTask',
  props: {
      task: {
          type: Object,
          required: true
      }
  },
  data: () => ({
      show: false,
      loading: false,
      isDone: true

  }),
  computed: {
    dayDiff: function() {
      const created = new Date(this.task.createdAt);
      const updated = new Date(this.task.updatedAt);
      const diffTime = Math.abs(created - updated);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
    }
  },
  methods: {
    toggleDone() {
      this.loading = true;

      axios.patch('/tasks/' + this.task.id, { done: false })
      .then((response) => {
        this.$store.commit('tasks/addTask', response.data);
        this.$store.commit('sumaryTasks/removeTask', response.data.id);
        this.loading = false;
      })
      
    }
  }
}
</script>
