import React, { Component } from 'react';
import { connect } from 'react-redux';

import { init, load, loadCurrencies } from "../ducks/common";

import CommonSection from '../sections/CommonSection';
import CommonSectionExtended from '../sections/CommonSectionExtended';
import ExtendedMode from "../sections/ExtendedMode";

class App extends Component {
  componentWillMount() {
    const { init, load, productId, loadCurrencies } = this.props;

    init(productId);
    load(productId);
    loadCurrencies();
  }

  render() {
    const { loaded, isExtendedMode } = this.props;

    if (!loaded) {
      return 'Загрузка...';
    }

    return (
        <>
        {isExtendedMode ? <CommonSectionExtended /> : <CommonSection />}
        {isExtendedMode ? <ExtendedMode /> : null}
        </>
    );
  }
}

function mapStateToProps(state, ownProps) {
  return {
    loaded: state.common.loaded,
    isExtendedMode: state.common.isExtendedMode,
  }
}

export default connect(mapStateToProps, { init, load, loadCurrencies })(App);
