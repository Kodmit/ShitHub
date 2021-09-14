import Vue from 'vue'
import Vuex from 'vuex'
import tasks from './tasks'
import sumaryTasks from './sumaryTasks'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: {
    tasks,
    sumaryTasks
  },
  strict: debug
})