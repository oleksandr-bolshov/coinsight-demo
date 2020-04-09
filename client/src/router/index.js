import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../views/Home';
import About from '../views/About';
import Login from '../views/Login';
import AuthGuard from '../components/AuthGuard';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    redirect: 'home',
  },
  {
    path: '/home',
    name: 'home',
    component: Home,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/about',
    name: 'about',
    component: About,
    meta: {
      requiresAuth: false,
    },
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      handleAuth: true,
    },
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '',
      component: AuthGuard,
      children: routes,
    },
  ],
});

export default router;
