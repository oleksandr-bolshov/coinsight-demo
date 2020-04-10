import {
  FETCH_CURRENT_USER,
  HAS_TOKENS,
  LOAD_TOKENS_FROM_STORAGE,
  LOGIN,
  LOGOUT,
  REFRESH_ACCESS_TOKEN,
  REGISTER,
  SET_ACCESS_TOKEN,
  SET_CURRENT_USER,
  SET_REFRESH_TOKEN,
} from './types';
import {
  login,
  logout,
  getCurrentUser,
  refreshAccessToken,
  register,
} from '../../api/auth';
import jwtDecode from 'jwt-decode';
import storage from '../../services/storage';

export default {
  async [REGISTER](_, {email, username, password}) {
    await register({
      email,
      username,
      password,
    });
  },

  async [LOGIN]({commit, dispatch}, {username, password}) {
    const result = await login({
      username,
      password,
    });

    commit(SET_ACCESS_TOKEN, result.data.accessToken);
    commit(SET_REFRESH_TOKEN, result.data.refreshToken);

    storage.setAccessToken(result.data.accessToken);
    storage.setRefreshToken(result.data.refreshToken);

    dispatch(FETCH_CURRENT_USER);
  },
  async [LOGOUT]({commit, state}) {
    const {sid} = jwtDecode(state.auth.refreshToken);
    await logout({id: sid});

    commit(SET_ACCESS_TOKEN, null);
    commit(SET_REFRESH_TOKEN, null);
    commit(SET_CURRENT_USER, null);

    storage.removeAccessToken();
    storage.removeRefreshToken();
  },
  async [LOAD_TOKENS_FROM_STORAGE]({commit}) {
    const accessToken = storage.getAccessToken();
    if (accessToken !== null) {
      commit(SET_ACCESS_TOKEN, accessToken);
    }

    const refreshToken = storage.getRefreshToken();
    if (refreshToken !== null) {
      commit(SET_REFRESH_TOKEN, refreshToken);
    }
  },
  async [REFRESH_ACCESS_TOKEN]({commit}) {
    const result = await refreshAccessToken();

    commit(SET_ACCESS_TOKEN, result.data.accessToken);

    storage.setAccessToken(result.data.accessToken);
  },
  async [FETCH_CURRENT_USER]({commit, getters}) {
    if (getters[HAS_TOKENS]) {
      const result = await getCurrentUser();

      commit(SET_CURRENT_USER, result.data);
    }
  },
};
