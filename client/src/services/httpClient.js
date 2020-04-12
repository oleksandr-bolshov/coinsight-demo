import axios from 'axios';
import storage from './storage';
import camelcaseKeys from 'camelcase-keys';
import snakeCaseKeys from 'snakecase-keys';
import config from '../config';

const axiosInstance = axios.create({
  baseURL: config.apiBaseUrl(),
  validateStatus: false,
});

axiosInstance.interceptors.response.use(response => response.data);

const _prepareResponse = response => {
  if ('data' in response) {
    response.data = camelcaseKeys(response.data, {deep: true});
  } else if ('error' in response) {
    let error = camelcaseKeys(response.error, {deep: true});
    throw new Error(JSON.stringify(error));
  }

  return response;
};

const _request = config => {
  config.params = snakeCaseKeys(config.params || {}, {deep: true});
  config.data = snakeCaseKeys(config.data || {}, {deep: true});

  let defaultOptions = {
    useAccessToken: false,
    useRefreshToken: false,
  };

  config.requestOptions = {
    ...defaultOptions,
    ...config.requestOptions,
  };

  config.headers = {
    ...config.headers,
    'X-Requested-With': 'XMLHttpRequest',
    'Content-type': 'application/json',
    Accept: 'application/json',
  };

  if (config.requestOptions.useAccessToken) {
    if (!storage.hasAccessToken()) {
      throw TypeError(`Access token is missing`);
    }

    config.headers['Authorization'] = 'Bearer ' + storage.getAccessToken();
  } else if (config.requestOptions.useRefreshToken) {
    if (!storage.hasRefreshToken()) {
      throw TypeError(`Refresh token is missing`);
    }

    config.headers['Authorization'] = 'Bearer ' + storage.getRefreshToken();
  }

  return axiosInstance.request({
    url: config.url,
    method: config.method,
    headers: config.headers,
    params: config.params,
    data: config.data,
  });
};

const get = async (
  url,
  {params = {}, headers = {}, requestOptions = {}} = {},
) => {
  let response = await _request({
    method: 'get',
    url,
    params,
    headers,
    requestOptions,
  });

  return _prepareResponse(response);
};

const post = async (url, data, {headers = {}, requestOptions = {}} = {}) => {
  let response = await _request({
    method: 'post',
    url,
    data,
    headers,
    requestOptions,
  });

  return _prepareResponse(response);
};

const put = async (url, data, {headers = {}, requestOptions = {}} = {}) => {
  let response = await _request({
    method: 'put',
    url,
    data,
    headers,
    requestOptions,
  });

  return _prepareResponse(response);
};

const destroy = async (url, {requestOptions = {}} = {}) => {
  let response = await _request({
    method: 'delete',
    url,
    requestOptions,
  });

  return _prepareResponse(response);
};

const httpClient = {get, post, put, destroy};

export default httpClient;
