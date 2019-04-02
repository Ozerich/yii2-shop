import React, { Component } from 'react';
import { connect } from 'react-redux';

import { load } from "../ducks/params";

import ParamsSection from '../sections/ParamsSection';
import PricesSection from '../sections/PricesSection';

class ExtendedMode extends Component {
  componentWillMount() {
    this.props.load()
  }

  render() {
    return (
        <>
        <ParamsSection />
        <PricesSection />
        </>
    );
  }
}

export default connect(null, { load })(ExtendedMode);