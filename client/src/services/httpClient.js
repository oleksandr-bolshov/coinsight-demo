import axios from 'axios';
import storage from './storage';
import camelcaseKeys from 'camelcase-keys';
import config from '../config';

const axiosInstance = axios.create({
  baseURL: config.apiBaseUrl(),
  validateStatus: false,
});

axiosInstance.interceptors.response.use(response => response.data);

class HttpClient {
  _prepareRequest(options) {
    let defaultOptions = {
      useAccessToken: false,
      useRefreshToken: false,
    };

    options = {
      ...defaultOptions,
      ...options,
    };

    let headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Content-type': 'application/json',
      Accept: 'application/json',
    };

    if (options.useAccessToken) {
      if (!storage.hasAccessToken()) {
        throw TypeError(`Access token is missing`);
      }

      headers['Authorization'] = 'Bearer ' + storage.getAccessToken();
    } else if (options.useRefreshToken) {
      if (!storage.hasRefreshToken()) {
        throw TypeError(`Refresh token is missing`);
      }

      headers['Authorization'] = 'Bearer ' + storage.getRefreshToken();
    }

    axiosInstance.defaults.headers.common = {
      ...axiosInstance.defaults.headers.common.headers,
      ...headers,
    };
  }

  _prepareResponse(response) {
    if ('data' in response) {
      response.data = camelcaseKeys(response.data, {deep: true});
    } else if ('error' in response) {
      let error = camelcaseKeys(response.error, {deep: true});
      throw new Error(JSON.stringify(error));
    }

    return response;
  }

  async get({url, headers = {}, params = {}, requestOptions = {}}) {
    this._prepareRequest(requestOptions);
    return this._prepareResponse(
      await axiosInstance.get(url, {
        params,
        headers,
      }),
    );
  }

  async post({url, data, headers = {}, requestOptions = {}}) {
    this._prepareRequest(requestOptions);
    return this._prepareResponse(
      await axiosInstance.post(url, data, {
        headers,
      }),
    );
  }

  async put({url, data, headers = {}, requestOptions = {}}) {
    this._prepareRequest(requestOptions);
    return this._prepareResponse(
      await axiosInstance.put(url, data, {
        headers,
      }),
    );
  }

  async delete({url, requestOptions = {}}) {
    this._prepareRequest(requestOptions);
    return this._prepareResponse(await axiosInstance.delete(url));
  }
}

export default HttpClient;
