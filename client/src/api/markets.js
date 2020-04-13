import httpClient from '../services/httpClient';

const globalStats = () =>
  httpClient.get('/global', {requestOptions: {useAccessToken: true}});

const coins = params =>
  httpClient.get('/coins', {
    params,
    requestOptions: {useAccessToken: true},
  });

export {globalStats, coins};
