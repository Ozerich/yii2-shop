import React, { Component } from 'react';
import { connect } from 'react-redux';

import { showForm } from "../../ducks/group-form";
import { deleteGroup } from "../../ducks/groups";

class GroupsListRow extends Component {
  render() {
    const { model } = this.props;
    return (
        <div className="list-row">
          <div className="field-list__name">
            {model.name}
          </div>
          <div className="field-list__actions">
            <button className="field-list__action btn btn-mini btn-primary" onClick={this.onEditClick.bind(this)}>
              Редактировать
            </button>
            <button className="field-list__action btn btn-mini btn-danger" onClick={this.onRemoveClick.bind(this)}>
              Удалить
            </button>
          </div>
        </div>
    );
  }

  onEditClick() {
    this.props.showForm(this.props.model.id);
  }

  onRemoveClick() {
    this.props.deleteGroup(this.props.model.id);
  }
}

export default connect(null, { showForm, deleteGroup })(GroupsListRow);
