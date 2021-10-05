<template>
  <v-card class="mx-auto mb-5" color="#0091a2" dark max-width="400" @mouseenter="displayNavigation = true" @mouseleave="displayNavigation = false">

    <v-progress-linear
      color="orange"
      indeterminate
      reverse
      v-if="loading"
    ></v-progress-linear>

    <section class="dly-navigation-btn" v-if="displayNavigation">
      <v-btn
        class="mx-2"
        fab
        dark
        small
        color="primary"
        @click="changeDay('left')"
      >
        <v-icon dark>
          mdi-chevron-left
        </v-icon>
      </v-btn>

      <v-btn
        class="mx-2"
        fab
        dark
        small
        color="primary"
        style="float: right"
        @click="changeDay('right')"
      >
      
        <v-icon dark>
          mdi-chevron-right
        </v-icon>
      </v-btn>
    </section>

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
      displayNavigation: false,
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
      
    },
    changeDay(direction) {
      this.loading = true;
      const doneAt = new Date(this.task.doneAt);

      if (direction === 'left') {
        var newDate = doneAt.setDate(doneAt.getDate() - 1);
      } else {
        var newDate = doneAt.setDate(doneAt.getDate() + 1);
      }

      axios.patch('/tasks/' + this.task.id, { doneAt: new Date(newDate) })
      .then((response) => {
        this.loading = false;
        this.$store.commit('sumaryTasks/removeTask', response.data.id);
        this.$store.commit('sumaryTasks/addTask', response.data);
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.dly-navigation-btn {
  position: absolute;
  z-index: 999;
  margin-top: 10px;
  width: 100%;
}
</style>