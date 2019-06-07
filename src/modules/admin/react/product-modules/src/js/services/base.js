import React from 'react';

const axios = require('axios');

const source = axios.CancelToken.source();

const api = axios.create({
  baseURL: document.location.href.indexOf('localhost') === -1 ? '/admin' : 'http://belmebel.local/admin',
  cancelToken: source.token
});

const resolve = res => JSON.parse(JSON.stringify(res.data));
const reject = err => {
  throw (err.response && err.response.data && err.response.data.errors) || { message: [err.message] };
};

export default class BaseService {
  query(url) {
    return api.get(url).then(resolve).catch(reject);
  }

  delete(url) {
    return api.delete(url).then(resolve).catch(reject);
  }

  post(url, data) {
    return api.post(url, data).then(resolve).catch(reject);
  }

  multipart(url, data) {
    const formData = new FormData();
    Object.keys(data).forEach(field => formData.append(field, data[field]));

    const config = {
      headers: {
        'content-type': 'multipart/form-data'
      }
    };

    return api.post(url, formData, config).then(resolve).catch(reject);
  }
}