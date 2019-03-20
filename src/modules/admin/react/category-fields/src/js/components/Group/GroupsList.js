import React, { Component } from 'react';
import { connect } from 'react-redux';
import { loadAll } from "../../ducks/groups";
import { showForm } from "../../ducks/group-form";

import GroupsListRow from './GroupsListRow';
import CreateButtonRow from "../Common/CreateButtonRow";
import GroupForm from "../Group/GroupForm";
import Loader from "../ui/Loader";

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
    const { groupFormVisible } = this.props;

    if (loading) {
      return <div className="list-body"><Loader /></div>;
    }

    if (groupFormVisible) {
      return <GroupForm />
    }

    return (
        <div className="list-body">
          <div className="list-row list-row--header">
            <div className="field-list__name">
              Название группы
            </div>
            <div className="field-list__actions">
              Действия
            </div>
          </div>

          {entities.map(model => <GroupsListRow key={model.id} model={model} />)}

          <CreateButtonRow label="Создать группу" onClick={this.onCreateClick.bind(this)} />
        </div>
    );
  }

  onCreateClick() {
    this.props.showForm();
  }
}

function mapStateToProps(state) {
  return {
    categoryId: state.common.categoryId,
    groups: state.groups,
    groupFormVisible: state.groupForm.opened
  };
}

export default connect(mapStateToProps, { loadAll, showForm })(GroupsList);
