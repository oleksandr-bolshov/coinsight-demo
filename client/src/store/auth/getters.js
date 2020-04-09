import {
  GET_CURRENT_USER,
  IS_ACCESS_TOKEN_NEED_REFRESH,
  HAS_TOKENS,
  IS_LOGGED_IN,
} from './types';
import jwtDecode from 'jwt-decode';
import {differenceInHours, fromUnixTime} from 'date-fns';

export default {
  [IS_LOGGED_IN]: state => !!state.user,
  [HAS_TOKENS]: state => !!state.auth.accessToken && !!state.auth.refreshToken,
  [GET_CURRENT_USER]: state => state.user,
  [IS_ACCESS_TOKEN_NEED_REFRESH]: state => {
    const {exp} = jwtDecode(state.auth.accessToken);
    return differenceInHours(fromUnixTime(exp), new Date()) < 12;
  },
};
