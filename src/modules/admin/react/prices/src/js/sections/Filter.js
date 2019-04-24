import React, { Component } from 'react';
import { connect } from 'react-redux';
import { load } from '../ducks/list';

class Filter extends Component {
  componentWillMount() {
    this.props.load();
  }

  render() {
    return null;
  }
}

export default connect(null, { load })(Filter);