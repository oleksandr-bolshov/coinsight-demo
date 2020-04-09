<template>
  <router-view />
</template>

<script>
import store from '../store';
import {
  FETCH_CURRENT_USER,
  IS_LOGGED_IN,
  LOAD_TOKENS_FROM_STORAGE,
} from '../store/auth/types';

export default {
  name: 'AuthGuard',

  async beforeRouteEnter(to, from, next) {
    if (!store.getters[`auth/${IS_LOGGED_IN}`]) {
      await store.dispatch(`auth/${LOAD_TOKENS_FROM_STORAGE}`);
      await store.dispatch(`auth/${FETCH_CURRENT_USER}`);
    }

    const isAuthenticatedRoute = to.matched.some(
      record => record.meta.requiresAuth,
    );
    const isAuthSectionRoute = to.matched.some(
      record => record.meta.handleAuth,
    );
    const isLoggedIn = store.getters[`auth/${IS_LOGGED_IN}`];

    if (!isLoggedIn && isAuthenticatedRoute) {
      next({
        name: 'login',
        query: {redirect: to.fullPath},
      });
    } else if (isLoggedIn && isAuthSectionRoute) {
      next({
        name: 'home',
      });
    } else {
      next();
    }
  },
};
</script>
