import httpClient from '../services/httpClient';

const profile = id =>
  httpClient.get(`/coins/${id}/profile`, {
    requestOptions: {useAccessToken: true},
  });

const marketData = id =>
  httpClient.get(`/coins/${id}/latest`, {
    requestOptions: {useAccessToken: true},
  });

const historicalData = (id, params) =>
  httpClient.get(`/coins/${id}/historical`, {
    params,
    requestOptions: {useAccessToken: true},
  });

export {profile, marketData, historicalData};
