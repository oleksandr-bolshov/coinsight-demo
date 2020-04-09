import {SET_ACCESS_TOKEN, SET_CURRENT_USER, SET_REFRESH_TOKEN} from './types';

export default {
  [SET_ACCESS_TOKEN](state, token) {
    state.auth.accessToken = token;
  },
  [SET_REFRESH_TOKEN](state, token) {
    state.auth.refreshToken = token;
  },
  [SET_CURRENT_USER](state, user) {
    state.user = user;
  },
};
