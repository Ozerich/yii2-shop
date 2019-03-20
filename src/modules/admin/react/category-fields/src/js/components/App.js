import React, { Component } from 'react';
import { connect } from 'react-redux';

import { init } from "../ducks/common";

class App extends Component {
  componentWillMount() {
    const { init, productId } = this.props;

    init(productId);
  }

  render() {
    const { loaded } = this.props;

    if (!loaded) {
      return 'Загрузка...';
    }

    return (
        <>
        <h1>Hello</h1>
        </>
    );
  }
}

function mapStateToProps(state, ownProps) {
  return {
    loaded: state.common.loaded
  }
}

export default connect(mapStateToProps, { init })(App);
