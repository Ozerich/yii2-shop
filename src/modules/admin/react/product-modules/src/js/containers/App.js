import React, { Component } from 'react';
import { connect } from 'react-redux';
import NewModule from './NewModule';

import { init } from "../ducks/common";
import List from "./List";

class App extends Component {
  componentWillMount() {
    this.props.init(this.props.productId);
  }

  render() {
    return (
        <>
          <List />
          <NewModule />
        </>
    );
  }
}

export default connect(null, { init })(App);