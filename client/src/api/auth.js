import HttpClient from '../services/httpClient';

let httpClient = new HttpClient();

const register = params =>
  httpClient.post({url: '/auth/register', data: params});

const login = params => httpClient.post({url: '/auth/login', data: params});

const getCurrentUser = () =>
  httpClient.get({
    url: '/auth/me',
    requestOptions: {useAccessToken: true},
  });

const logout = params =>
  httpClient.put({
    url: '/sessions/terminate',
    data: params,
    requestOptions: {useAccessToken: true},
  });

const refreshAccessToken = () =>
  httpClient.get({
    url: '/sessions/access-token',
    requestOptions: {useRefreshToken: true},
  });

export {register, login, getCurrentUser, logout, refreshAccessToken};
