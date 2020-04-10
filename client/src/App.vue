<template>
  <v-app>
    <v-app-bar app dark color="#363557" elevation="0">
      <div class="d-flex align-center pl-8">
        <v-toolbar-title>
          <h2>
            Coinsight
          </h2>
        </v-toolbar-title>
      </div>

      <v-spacer></v-spacer>

      <v-flex align-center justify-end row class="pr-8" v-if="isLoggedIn">
        <v-btn icon class="notifications">
          <v-icon color="#c9c8df">
            mdi-bell
          </v-icon>
        </v-btn>
        <span class="px-4 white--text">{{ user.username }}</span>
        <v-menu bottom offset-y>
          <template v-slot:activator="{on}">
            <v-icon v-on="on" color="#c9c8df">
              mdi-chevron-down
            </v-icon>
          </template>
          <v-list flat>
            <v-list-item @click="onLogout">
              Log Out
            </v-list-item>
          </v-list>
        </v-menu>
      </v-flex>
      <div v-else class="pr-8">
        <v-btn color="primary" :to="{name: 'login'}" class="mr-2">
          Login
        </v-btn>
        <v-btn color="primary" outlined :to="{name: 'register'}">
          Sign up
        </v-btn>
      </div>
    </v-app-bar>

    <v-content>
      <router-view />
    </v-content>
  </v-app>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import {
  GET_CURRENT_USER,
  IS_ACCESS_TOKEN_NEED_REFRESH,
  IS_LOGGED_IN,
  LOGOUT,
  REFRESH_ACCESS_TOKEN,
} from './store/auth/types';

export default {
  name: 'App',

  async mounted() {
    const hour_in_milliseconds = 3600000;

    const refreshAccessToken = async () => {
      if (this.isLoggedIn && this.isAccessTokenNeedRefresh) {
        await this.refreshAccessToken();
      }
    };

    await refreshAccessToken();
    setInterval(() => refreshAccessToken(), hour_in_milliseconds);
  },

  methods: {
    ...mapActions('auth', {
      logout: LOGOUT,
      refreshAccessToken: REFRESH_ACCESS_TOKEN,
    }),

    async onLogout() {
      await this.logout();
      await this.$router.push({name: 'login'});
    },
  },

  computed: {
    ...mapGetters('auth', {
      user: GET_CURRENT_USER,
      isAccessTokenNeedRefresh: IS_ACCESS_TOKEN_NEED_REFRESH,
      isLoggedIn: IS_LOGGED_IN,
    }),
  },
};
</script>
