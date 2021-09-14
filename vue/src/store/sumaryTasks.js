// initial state
const state = () => ({
    all: []
})

// getters
const getters = {}

// mutations
const mutations = {
    addTask(state, task) {
        state.all.unshift(task);
    },
    setTasks(state, tasks) {
        state.all = tasks;
    },
    removeTask(state, taskId) {
        state.all = state.all.filter(
            task => task.id !== taskId
        );
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations
}