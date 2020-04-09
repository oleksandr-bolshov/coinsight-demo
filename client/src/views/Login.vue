<template>
  <v-row align="center" justify="center">
    <v-col cols="5">
      <h1 class="pa-4">Welcome to coinsight!</h1>
      <v-form autocomplete="off" v-model="isFormValid">
        <v-text-field
          @keyup.enter="onLogin"
          label="Username"
          name="username"
          type="text"
          v-model="input.username"
          outlined
          dark
          flat
          :disabled="isPending"
          :rules="[v => !!v || 'Username is required']"
        />

        <v-text-field
          id="password"
          @keyup.enter="onLogin"
          label="Password"
          name="password"
          type="password"
          v-model="input.password"
          outlined
          :disabled="isPending"
          :rules="[v => !!v || 'Password is required']"
        />
        <v-btn
          @click="onLogin"
          block
          dark
          elevation="0"
          :disabled="!isFormValid"
          :loading="isPending"
          color="primary"
          height="4em"
        >
          Login
        </v-btn>
        <div class="text-center py-4"><span>or</span></div>
        <v-btn
          color="primary"
          outlined
          height="4em"
          block
          :to="{name: 'register'}"
        >
          Sign up
        </v-btn>
      </v-form>
    </v-col>
  </v-row>
</template>

<script>
import {LOGIN} from '../store/auth/types';
import {mapActions} from 'vuex';

export default {
  name: 'Login',
  data() {
    return {
      isPending: false,
      input: {
        username: '',
        password: '',
      },
      isFormValid: false,
    };
  },
  methods: {
    async onLogin() {
      if (this.input.username !== '' && this.input.password !== '') {
        this.isPending = true;

        try {
          await this.login({
            username: this.input.username,
            password: this.input.password,
          });

          if (this.$route.query.redirect) {
            await this.$router.push({path: this.$route.query.redirect});
          } else {
            await this.$router.push({name: 'home'});
          }
        } catch (e) {
          alert(e);
        }

        this.isPending = false;
      }
    },

    ...mapActions('auth', {
      login: LOGIN,
    }),
  },
};
</script>

<style scoped lang="scss">
.horizontal-line {
  display: flex;
  flex-direction: row;
}
.horizontal-line:before,
.horizontal-line:after {
  content: '';
  flex: 1 1;
  border-bottom: 1px solid #c9c8df;
  margin: auto;
}
.horizontal-line:before {
  margin-right: 0.5em;
}
.horizontal-line:after {
  margin-left: 0.5em;
}
</style>
