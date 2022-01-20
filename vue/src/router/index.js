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
          component: () => import(/* webpackChunkName: "home" */ '@/views/Home.vue')
        },
        {
          path: '/reports',
          name: 'reports',
          component: () => import(/* webpackChunkName: "reportes" */ '@/views/Reports.vue')
        },
        {
          path: '/users',
          name: 'users',
          component: () => import(/* webpackChunkName: "users" */ '@/views/Users.vue')
        },
        {
          path: '/users/:id',
          name: 'users-profile',
          component: () => import(/* webpackChunkName: "users-profile" */ '@/views/UserProfile.vue')
        },
        {
          path: '/users/:id/edit',
          name: 'users-edit',
          component: () => import(/* webpackChunkName: "users-edit" */ '@/views/UserEdit.vue')
        },
        {
          path: '/users/create',
          name: 'users-create',
          component: () => import(/* webpackChunkName: "users-create" */ '@/views/UserCreate.vue')
        },
        {
          path: '/todo',
          name: 'todo',
          component: () => import(/* webpackChunkName: "users-create" */ '@/views/ToDo.vue')
        },
        {
          path: '/g-auth/redirect-uri',
          name: 'g-auth-redirect-uri',
          component: () => import(/* webpackChunkName: "users-create" */ '@/views/GAuthRedirection.vue')
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
