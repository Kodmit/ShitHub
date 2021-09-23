<template>
  <v-card class="mx-auto mb-5" color="#0091a2" dark max-width="400">

    <v-progress-linear
      color="orange"
      indeterminate
      reverse
      v-if="loading"
    ></v-progress-linear>

    <v-card-text
      class="body-1"
      style="color: #fff" 
      @mouseenter="showEdit = true"
      @mouseleave="showEdit = false"
    >

      <v-btn icon style="float: right" v-if="showEdit && !showTextArea" @click="showTextArea = true">
        <v-icon>
          mdi-pencil
        </v-icon>
      </v-btn>

      <section v-if="showTextArea">

        <form @submit.prevent="updateTask()">

          <v-textarea
            v-model="form.description"
            style="margin-bottom: -15px"
            outlined
            label="Modifier le texte"
          ></v-textarea>

          <v-btn :disabled="loading" fab depressed small @click="showTextArea = false">
            <v-icon>
              mdi-arrow-left-top
            </v-icon>
          </v-btn>
          <v-btn class="ml-3" :disabled="loading" fab depressed color="red" small @click="deleteTask()">
            <v-icon>
              mdi-delete
            </v-icon>
          </v-btn>
          <v-btn :disabled="loading" fab depressed color="primary" style="float: right" small type="submit">
            <v-icon>
              mdi-content-save
            </v-icon>
          </v-btn>

        </form>
      </section>

      <span v-else>{{ task.description }}</span>
    </v-card-text>

    <v-card-actions class="mt-0">
      <v-list-item class="grow">
          
        {{ moment(task.createdAt).fromNow() }}
        
        <v-row justify="end">
          
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <div v-on="on" v-bind="attrs">
                <v-switch @click="toggleDone()" color="orange" hide-details></v-switch>
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
import Swal from 'sweetalert2';

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
      showEdit: false,
      showTextArea: false,
      form: {
        description: ''
      }
  }),
  mounted() {
    this.form.description = this.task.description;
  },
  methods: {
    toggleDone() {
      this.loading = true;

      axios.patch('/tasks/' + this.task.id, { done: true })
      .then((response) => {
        this.$store.commit('tasks/removeTask', response.data.id);
        this.$store.commit('sumaryTasks/addTask', response.data);
        this.loading = false;
      })
    },
    updateTask() {
      this.loading = true;

      axios.put('/tasks/' + this.task.id, { description: this.form.description })
      .then((response) => {
        this.task = response.data;
        this.loading = false;
        this.showTextArea = false;
      })
    },
    deleteTask() {
      Swal.fire({
        title: 'Voulez-vous vraiment supprimer cette tâche ?',
        showCancelButton: true,
        confirmButtonText: 'Supprimer',
        denyButtonText: `Retour`,
      }).then((result) => {
        if (result.isConfirmed) {
          this.loading = true;
          axios.delete('/tasks/' + this.task.id)
          .then(() => {
            this.loading = false;
            this.$store.commit('tasks/removeTask', this.task.id);
            Swal.fire('Tâche supprimée', '', 'success')
          })
        }
      })
    }
  }
}
</script>
