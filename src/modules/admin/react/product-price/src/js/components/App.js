import React, { Component } from 'react';
import { connect } from 'react-redux';

import { init } from "../ducks/common";
import { load } from "../ducks/params";

import ParamsSection from '../sections/ParamsSection';
import PricesSection from '../sections/PricesSection';

class App extends Component {
  componentWillMount() {
    const { init, load, productId } = this.props;

    init(productId);
    load(productId);
  }

  render() {
    const { loaded } = this.props;

    if (!loaded) {
      return 'Загрузка...';
    }

    return (
        <>
        <ParamsSection />
        <PricesSection />
        </>
    );
  }
}

function mapStateToProps(state, ownProps) {
  return {
    loaded: state.common.loaded
  }
}

export default connect(mapStateToProps, { init, load })(App);
