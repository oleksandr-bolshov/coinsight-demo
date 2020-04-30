import httpClient from '../services/httpClient';

const getUserPortfolios = params =>
  httpClient.get('/portfolios', {
    params,
    requestOptions: {useAccessToken: true},
  });

const createPortfolio = params =>
  httpClient.post('/portfolios', params, {
    requestOptions: {useAccessToken: true},
  });

const getPortfolioReport = id =>
  httpClient.get(`/portfolios/${id}`, {requestOptions: {useAccessToken: true}});

const getPortfolioTransactions = params =>
  httpClient.get('/transactions', {
    params,
    requestOptions: {useAccessToken: true},
  });

const addTransaction = params =>
  httpClient.post('/transactions', params, {
    requestOptions: {useAccessToken: true},
  });

export {
  getUserPortfolios,
  createPortfolio,
  getPortfolioReport,
  getPortfolioTransactions,
  addTransaction,
};
