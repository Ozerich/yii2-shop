import React, { Component } from 'react';
import { connect } from 'react-redux';

import { init } from "../ducks/common";
import { loadAll as loadFields } from "../ducks/fields";
import { loadAll as loadGroups } from "../ducks/groups";

import FieldsList from '../components/Field/FieldsList';
import GroupsList from '../components/Group/GroupsList';

class App extends Component {
  async componentWillMount() {
    const { init, categoryId, loadGroups, loadFields } = this.props;

    init(categoryId);

    await loadGroups(categoryId);
    loadFields(categoryId);
  }

  render() {
    const { loaded } = this.props;

    if (!loaded) {
      return 'Загрузка...';
    }

    return (
        <>
        <GroupsList />
        <FieldsList />
        </>
    );
  }
}

function mapStateToProps(state, ownProps) {
  return {
    loaded: state.common.loaded
  }
}

export default connect(mapStateToProps, { init, loadGroups, loadFields })(App);
