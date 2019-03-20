import React, { Component } from 'react';

class GroupsListRow extends Component {
  render() {
    const { model } = this.props;
    return (
        <div className="list-row">
          <div className="field-list__name">
            {model.name}
          </div>
          <div className="field-list__actions">
            <button className="field-list__action btn btn-mini btn-primary">Редактировать</button>
            <button className="field-list__action btn btn-mini btn-danger">Удалить</button>
          </div>
        </div>
    );
  }
}

export default GroupsListRow;
