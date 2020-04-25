import httpClient from '../services/httpClient';

const register = params => httpClient.post('/auth/register', params);

const login = params => httpClient.post('/auth/login', params);

const getCurrentUser = () =>
  httpClient.get('/auth/me', {requestOptions: {useAccessToken: true}});

const logout = params =>
  httpClient.put('/sessions/terminate', params, {
    requestOptions: {useAccessToken: true},
  });

const refreshAccessToken = () =>
  httpClient.get('/sessions/refresh', {
    requestOptions: {useRefreshToken: true},
  });

export {register, login, getCurrentUser, logout, refreshAccessToken};
