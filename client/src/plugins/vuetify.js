import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import '../scss/main.scss';

Vue.use(Vuetify);

export default new Vuetify({
  theme: {
    dark: true,
    themes: {
      dark: {
        primary: '#c353e7',
        text: {
          base: '#dcdbf2',
          darken1: '#b7b6cd',
          darken2: '#9e9db5',
          lighten1: '#f4f5ff',
          lighten2: '#ffffff',
        },
        background: '#222337',
        surface: {
          base: '#313051',
          lighten2: '#4e4e7c',
          lighten3: '#7a7ab1',
        },
      },
    },
    options: {
      customProperties: true,
    },
  },
});
