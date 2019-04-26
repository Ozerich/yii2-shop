import React, { Component } from 'react';
import { Provider } from 'react-redux';

import store from './store';
import App from "./components/App";

class Application extends Component {
  render() {
    return (
        <Provider store={store}>
          <App />
        </Provider>
    );
  }
}

export default Application;
