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
    return (
        <>
        <GroupsList />
        <FieldsList />
        </>
    );
  }
}


export default connect(null, { init, loadGroups, loadFields })(App);
