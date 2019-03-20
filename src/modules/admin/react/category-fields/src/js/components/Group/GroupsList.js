import React, { Component } from 'react';
import { connect } from 'react-redux';
import { loadAll } from "../../ducks/groups";

import GroupsListRow from './GroupsListRow';
import CreateButtonRow from "../Common/CreateButtonRow";

class GroupsList extends Component {
  componentWillMount() {
  }

  render() {
    const { loading, entities } = this.props.groups;

    return (
        <div className="list">
          <div className="list-title">Группы полей</div>

          {this.renderBody()}
        </div>
    );
  }

  renderBody() {
    const { loading, entities } = this.props.groups;

    if (loading) {
      return <h1>Loading</h1>
    }

    return (
        <>
        <div className="list-row list-row--header">
          <div className="field-list__name">
            Название группы
          </div>
          <div className="field-list__actions">
            Действия
          </div>
        </div>

        {entities.map(model => <GroupsListRow key={model.id} model={model} />)}

        <CreateButtonRow label="Создать группу" />
        </>
    );
  }
}

function mapStateToProps(state) {
  return {
    categoryId: state.common.categoryId,
    groups: state.groups
  };
}

export default connect(mapStateToProps, { loadAll })(GroupsList);
