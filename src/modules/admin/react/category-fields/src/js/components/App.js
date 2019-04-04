import React, { Component } from 'react';
import { connect } from 'react-redux';

import { init } from "../ducks/common";
import { loadAll as loadFields } from "../ducks/fields";
import { loadAll as loadGroups } from "../ducks/groups";

import FieldsList from '../components/Field/FieldsList';
import GroupsList from '../components/Group/GroupsList';
import ActiveFields from '../components/ActiveFields/ActiveFields';
import BlockOrLoader from "./ui/BlockOrLoader";

class App extends Component {
  async componentWillMount() {
    const { init, categoryId, loadGroups, loadFields } = this.props;

    init(categoryId);

    await loadGroups(categoryId);
    loadFields(categoryId);
  }

  render() {
    const { loading, type } = this.props;

    return (
        <BlockOrLoader loading={loading}>
          <ActiveFields />
          <FieldsList />
          <GroupsList />
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

export default connect(mapStateToProps, { init, loadGroups, loadFields })(App);
