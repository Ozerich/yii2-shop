import React, { Component } from 'react';
import { Provider } from 'react-redux';


import store from './store';
import Filter from "./sections/Filter";
import List from "./sections/List";

class Application extends Component {
  componentWillMount(){

  }
  render() {
    return (
        <Provider store={store}>
          <Filter />
          <List />
        </Provider>
    );
  }
}

export default Application;
