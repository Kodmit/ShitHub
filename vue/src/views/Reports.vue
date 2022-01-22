<template>
  <div>
    <p class="text-h3 dly-cat-title">Rapports</p>
    Du <b>{{ moment(startDate).format("dddd LL") }}</b> au
    <b>{{ moment(endDate).format("dddd LL") }}</b>

    <section style="float: right">
      <v-btn depressed @click="removeWeek()" class="mr-5"
        >Semaine précédente</v-btn
      >
      <v-btn depressed @click="addWeek()">Semaine suivante</v-btn>
    </section>

    <v-card flat tile class="d-flex justify-space-between mb-6">
      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">LUNDI</div>
            <sumary-task
              v-for="task in getTasksForDay('Monday')"
              :key="task.id"
              :task="task"
            />
          </v-list-item-content>
        </v-list-item>
      </v-card>

      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">MARDI</div>
            <sumary-task
              v-for="task in getTasksForDay('Tuesday')"
              :key="task.id"
              :task="task"
            />
          </v-list-item-content>
        </v-list-item>
      </v-card>

      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">MERCREDI</div>
            <sumary-task
              v-for="task in getTasksForDay('Wednesday')"
              :key="task.id"
              :task="task"
            />
          </v-list-item-content>
        </v-list-item>
      </v-card>

      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">JEUDI</div>
            <sumary-task
              v-for="task in getTasksForDay('Thursday')"
              :key="task.id"
              :task="task"
            />
          </v-list-item-content>
        </v-list-item>
      </v-card>

      <v-card class="mx-auto" width="390" flat tile>
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">VENDREDI</div>
            <sumary-task
              v-for="task in getTasksForDay('Friday')"
              :key="task.id"
              :task="task"
            />
          </v-list-item-content>
        </v-list-item>
      </v-card>
    </v-card>
  </div>
</template>

<script>
import axios from "axios";
import SumaryTask from "../components/SumaryTask.vue";

export default {
  name: "Reports",
  components: { SumaryTask },
  data() {
    return {
      tasks: [],
      startDate: null,
      endDate: new Date(),
    };
  },
  computed: {
    currentDate: function() {
      return new Date();
    },
  },
  methods: {
    getMonday(d) {
      d = new Date(d);
      var day = d.getDay(),
        diff = d.getDate() - day + (day == 0 ? -6 : 1);
      return new Date(d.setDate(diff));
    },
    removeWeek() {
      this.startDate.setDate(this.startDate.getDate() - 7);
      this.endDate.setDate(this.endDate.getDate() - 7);
      this.fetchTasksForDate();
    },
    addWeek() {
      this.startDate.setDate(this.startDate.getDate() + 7);
      this.endDate.setDate(this.endDate.getDate() + 7);
      this.fetchTasksForDate();
    },
    fetchTasksForDate(startDate, endDate) {
      axios
        .get("/tasks", {
          params: {
            startDate: this.startDate,
            endDate: this.endDate,
            done: true,
          },
        })
        .then((response) => {
          this.tasks = response.data;
        });
    },
    getTasksForDay(day) {
      return this.tasks.filter((task) => {
        const weekDay = new Date(task.updatedAt).toLocaleDateString("en-US", {
          weekday: "long",
        });
        if (weekDay === day) {
          return task;
        }
      });
    },
  },
  mounted() {
    this.startDate = this.getMonday(new Date());
    this.endDate.setDate(this.startDate.getDate() + 7);
    this.fetchTasksForDate();
  },
};
</script>

<style scoped>
.dly-cat-title {
  font-size: 1.4rem !important;
  line-height: 2rem;
  letter-spacing: 3px !important;
  font-family: "Roboto", sans-serif !important;
  text-transform: uppercase;
}
</style>
