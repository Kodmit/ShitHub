import Vue from 'vue'
import VueRouter from 'vue-router'
import DisconnectedLayout from '../layouts/Disconnected.vue'
import ConnectedLayout from '../layouts/Connected.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'disconnected',
    component: DisconnectedLayout,
    children: [
        {
            path: '/login',
            name: 'login',
            component: () => import(/* webpackChunkName: "about" */ '../views/Login.vue')
        }
    ]
  },
  {
    path: '/',
    name: 'connected',
    component: ConnectedLayout,
    children: [
        {
          path: '/home',
          name: 'home',
          component: () => import(/* webpackChunkName: "login" */ '@/views/Home.vue')
        },
        {
          path: '/reports',
          name: 'reports',
          component: () => import(/* webpackChunkName: "login" */ '@/views/Reports.vue')
        },
        {
          path: '/users',
          name: 'users',
          component: () => import(/* webpackChunkName: "login" */ '@/views/Users.vue')
        },
        {
          path: '/users/create',
          name: 'users-create',
          component: () => import(/* webpackChunkName: "login" */ '@/views/UserCreate.vue')
        }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
