import React, { Component } from 'react';
import { connect } from 'react-redux';
import { loadAll } from "../../ducks/fields";
import CreateButtonRow from "../Common/CreateButtonRow";
import FieldsListRow from "./FieldsListRow";

class FieldsList extends Component {
  componentWillMount() {
  }


  render() {
    const { loading, entities } = this.props.fields;

    return (
        <div className="list">
          <div className="list-title">Поля</div>

          {this.renderBody()}
        </div>
    );
  }

  renderBody() {
    const { loading, entities } = this.props.fields;

    if (loading) {
      return <h1>Loading</h1>
    }

    return (
        <div className="list-body">
          <div className="list-row list-row--header">
            <div className="field-list__name">
              Название параметра
            </div>
            <div className="field-list__group">
              Группа
            </div>
            <div className="field-list__type">
              Тип поля
            </div>
            <div className="field-list__actions">
              Действия
            </div>
          </div>

          {entities.map(model => <FieldsListRow key={model.id} model={model} />)}

          <CreateButtonRow label="Создать параметр" />
        </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    categoryId: state.common.categoryId,
    fields: state.fields
  };
}

export default connect(mapStateToProps, { loadAll })(FieldsList);
