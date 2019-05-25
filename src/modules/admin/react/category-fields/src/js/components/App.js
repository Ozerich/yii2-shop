import React, { Component } from 'react';
import { connect } from 'react-redux';

import { init } from "../ducks/common";
import { loadAll as loadFields } from "../ducks/fields";

import FieldsList from '../components/Field/FieldsList';
import ActiveFields from '../components/ActiveFields/ActiveFields';
import BlockOrLoader from "./ui/BlockOrLoader";

class App extends Component {
  async componentWillMount() {
    const { init, categoryId, loadFields } = this.props;

    init(categoryId);

    loadFields(categoryId);
  }

  render() {
    const { loading } = this.props;

    return (
        <BlockOrLoader loading={loading}>
          <ActiveFields />
          <FieldsList />
        </BlockOrLoader>
    );
  }
}

function mapStateToProps(state) {
  return {
    loading: state.common.loading,
    type: state.common.model ? state.common.model.type : null
  }
}

export default connect(mapStateToProps, { init, loadFields })(App);
