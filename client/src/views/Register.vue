<template>
  <v-row align="center" justify="center">
    <v-col cols="5">
      <h1 class="pa-4">Welcome to coinsight</h1>
      <v-form autocomplete="off" v-model="isFormValid">
        <v-text-field
          @keyup.enter="onRegister"
          label="Email"
          name="email"
          type="email"
          v-model="input.email"
          outlined
          dark
          flat
          :disabled="isPending"
          :rules="emailRules"
        />

        <v-text-field
          @keyup.enter="onRegister"
          label="Username"
          name="username"
          type="text"
          v-model="input.username"
          outlined
          dark
          flat
          :disabled="isPending"
          :rules="usernameRules"
        />

        <v-text-field
          id="password"
          @keyup.enter="onRegister"
          label="Password"
          name="password"
          type="password"
          v-model="input.password"
          outlined
          :disabled="isPending"
          :rules="passwordRules"
        />

        <v-text-field
          id="password"
          @keyup.enter="onRegister"
          label="Repeat password"
          name="repeated"
          type="password"
          v-model="input.repeated"
          outlined
          :disabled="isPending"
          :rules="repeatedRules"
        />

        <v-btn
          @click="onRegister"
          block
          dark
          elevation="0"
          :disabled="!isFormValid"
          :loading="isPending"
          color="primary"
          height="4em"
        >
          Sign up
        </v-btn>
        <div class="text-center py-4"><span>or</span></div>
        <v-btn
          color="primary"
          outlined
          height="4em"
          block
          :to="{name: 'login'}"
        >
          Login
        </v-btn>
      </v-form>
    </v-col>
  </v-row>
</template>

<script>
import {mapActions} from 'vuex';
import {REGISTER} from '../store/auth/types';

export default {
  name: 'Register',
  data() {
    return {
      isPending: false,
      input: {
        email: '',
        username: '',
        password: '',
        repeated: '',
      },
      isFormValid: false,
      emailRules: [
        v => !!v || 'E-mail is required',
        v => this.isEmailValid(v) || 'E-mail must be valid',
      ],
      usernameRules: [
        v => !!v || 'Username is required',
        v =>
          (v && v.length >= 3 && this.isUsernameValid(v)) ||
          'Enter the correct information',
      ],
      passwordRules: [
        v => !!v || 'Password is required',
        v =>
          v.length >= 8 || 'Password must be equal or more than 8 characters',
      ],
      repeatedRules: [
        v => !!v || 'Repeated password is required',
        v => v === this.input.password || 'Passwords should match',
      ],
    };
  },
  methods: {
    async onRegister() {
      if (
        this.input.email !== '' &&
        this.input.username !== '' &&
        this.input.password !== ''
      ) {
        this.isPending = true;

        try {
          await this.register({
            email: this.input.email,
            username: this.input.username,
            password: this.input.password,
          });

          alert('You are successfully registered.');

          if (this.$route.query.redirect) {
            await this.$router.push({
              name: 'login',
              query: {redirect: this.$route.query.redirect},
            });
          } else {
            await this.$router.push({name: 'login'});
          }
        } catch (e) {
          alert(e);
        }

        this.isPending = false;
      }
    },

    isEmailValid(email) {
      return /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/.test(email);
    },

    isUsernameValid(username) {
      return /^(?=[a-zA-Z0-9._]{3,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/.test(
        username,
      );
    },

    ...mapActions('auth', {
      register: REGISTER,
    }),
  },
};
</script>

<style scoped></style>
