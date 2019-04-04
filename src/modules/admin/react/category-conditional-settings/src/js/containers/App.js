import React, { Component } from 'react';
import { connect } from 'react-redux';
import Conditions from "./Conditions";
import Fields from "./Fields";

import BlockOrLoader from '../components/ui/BlockOrLoader';

import { init } from "../ducks/common";

class App extends Component {
  componentWillMount() {
    const { init, categoryId } = this.props;
    init(categoryId);
  }

  render() {
    return (
        <BlockOrLoader loading={this.props.loading}>
          <Conditions />
          <Fields />
        </BlockOrLoader>
    );
  }
}

function mapStateToProps(state) {
  return { loading: state.common.loading }
}

export default connect(mapStateToProps, { init })(App);