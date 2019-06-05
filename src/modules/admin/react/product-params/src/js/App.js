import React, { Component } from 'react';
import { Provider } from 'react-redux';

import store from './store';

import App from './containers/App';

class Application extends Component {
  render() {
    return (
        <Provider store={store}>
          <App {...this.props} />
        </Provider>
    );
  }
}

export default Application;
