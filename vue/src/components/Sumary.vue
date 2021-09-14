<template>
  <v-card flat tile class="d-flex justify-space-between mb-6">

      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">LUNDI</div>
            <sumary-task v-for="task in getTasksForDay('Monday')" :key="task.id" :task="task" />
          </v-list-item-content>
        </v-list-item>
      </v-card>



      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">MARDI</div>
            <sumary-task v-for="task in getTasksForDay('Tuesday')" :key="task.id" :task="task" />
          </v-list-item-content>
        </v-list-item>
      </v-card>


      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">MERCREDI</div>
            <sumary-task v-for="task in getTasksForDay('Wednesday')" :key="task.id" :task="task" />
          </v-list-item-content>
        </v-list-item>
      </v-card>
 


      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">JEUDI</div>
            <sumary-task v-for="task in getTasksForDay('Thursday')" :key="task.id" :task="task" />
          </v-list-item-content>
        </v-list-item>
      </v-card>



      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">VENDREDI</div>
            <sumary-task v-for="task in getTasksForDay('Friday')" :key="task.id" :task="task" />
          </v-list-item-content>
        </v-list-item>
      </v-card>

  </v-card>
</template>

<script>
import axios from "axios";
import SumaryTask from './SumaryTask.vue';

export default {
  components: { SumaryTask },
  name: "Sumary",
  computed: {
      tasks: function() {
        return this.$store.state.sumaryTasks.all;
      }
  },
  methods: {
    getMonday(d) {
      d = new Date(d);
      var day = d.getDay(),
        diff = d.getDate() - day + (day == 0 ? -6 : 1);
      return new Date(d.setDate(diff));
    },
    getTasksForDay(day) {
        return this.tasks.filter((task) => {
            const weekDay = new Date(task.updatedAt).toLocaleDateString('en-US', { weekday: 'long'});
            if (weekDay === day) {
                return task;
            }
        });
    }
  },
  mounted() {
    axios
      .get("/tasks", {
        params: {
          startDate: this.getMonday(new Date()),
          done: true
        },
      })
      .then((response) => {
        this.$store.commit('sumaryTasks/setTasks', response.data);
      });
  },
};
</script>
