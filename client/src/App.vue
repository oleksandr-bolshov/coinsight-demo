<template>
  <v-app>
    <v-app-bar app clipped-left dark elevation="0">
      <div class="d-flex align-center">
        <v-toolbar-title>
          <h1 class="display-1 gray--text text--lighten-5">
            coinsight
          </h1>
        </v-toolbar-title>
      </div>

      <v-spacer></v-spacer>

      <v-flex align-center justify-end row class="pr-3" v-if="isLoggedIn">
        <v-btn small icon class="notifications">
          <v-icon>
            mdi-bell
          </v-icon>
        </v-btn>
        <span class="px-4 white--text">{{ user.username }}</span>
        <v-menu bottom offset-y>
          <template v-slot:activator="{on}">
            <v-icon v-on="on">
              mdi-chevron-down
            </v-icon>
          </template>
          <v-list>
            <v-list-item @click="onLogout">
              Log Out
            </v-list-item>
          </v-list>
        </v-menu>
      </v-flex>
      <div v-else class="pr-3">
        <v-btn color="primary" :to="{name: 'login'}" class="mr-2">
          Login
        </v-btn>
        <v-btn color="primary" outlined :to="{name: 'register'}">
          Sign up
        </v-btn>
      </div>
    </v-app-bar>

    <v-navigation-drawer
      app
      floating
      clipped
      width="76"
      class="navigation-drawer"
      v-model="displayDrawer"
    >
      <v-list class="sidebar" dense>
        <v-list-item
          v-for="(link, index) in sidebarLinks"
          :key="index"
          :to="link.routeTo"
        >
          <v-list-item-content>
            <div class="d-flex flex-column">
              <v-icon>{{ link.icon }}</v-icon>
              <div class="caption text-center">
                {{ link.title }}
              </div>
            </div>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-content>
      <router-view />
    </v-content>
  </v-app>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import {
  GET_CURRENT_USER,
  HAS_TOKENS,
  IS_ACCESS_TOKEN_NEED_REFRESH,
  IS_LOGGED_IN,
  LOGOUT,
  REFRESH_ACCESS_TOKEN,
} from './store/auth/types';

export default {
  name: 'App',

  data() {
    return {
      sidebarLinks: [
        {
          routeTo: {name: 'portfolio'},
          icon: 'mdi-briefcase',
          title: 'Portfolio',
        },
        {
          routeTo: {name: 'markets'},
          icon: 'mdi-finance',
          title: 'Markets',
        },
        {
          routeTo: {name: 'home'},
          icon: 'mdi-home-outline',
          title: 'Home',
        },
      ],
    };
  },

  async mounted() {
    const hour_in_milliseconds = 3600000;

    const refreshAccessToken = async () => {
      if (this.hasTokens && this.isAccessTokenNeedRefresh) {
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
      hasTokens: HAS_TOKENS,
    }),

    displayDrawer: {
      set() {},
      get() {
        return this.isLoggedIn;
      },
    },
  },
};
</script>

<style scoped lang="scss">
.sidebar {
  .v-icon,
  .caption {
    color: var(--v-text-darken2) !important;
  }

  .v-list-item--active {
    .v-icon,
    .caption {
      color: var(--v-primary-base) !important;
    }
  }

  .v-list-item--active.v-list-item {
    border-left: 0.25em solid var(--v-primary-base);
    padding-left: 0;
  }

  .v-list-item--active.v-list-item--link::before {
    background-color: var(--v-primary-base);
    opacity: 0.1;
  }

  .v-list-item:not(.v-list-item--active):not(.v-list-item--disabled) {
    color: var(--v-surface-lighten3) !important;
  }

  .v-list-item {
    padding: 0 0.25em;
    height: 76px;
  }
}
</style>
