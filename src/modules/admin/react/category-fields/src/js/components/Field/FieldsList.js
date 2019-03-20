import React, { Component } from 'react';
import { connect } from 'react-redux';
import { loadAll } from "../../ducks/fields";
import { showForm } from "../../ducks/field-form";
import CreateButtonRow from "../Common/CreateButtonRow";
import FieldsListRow from "./FieldsListRow";
import FieldForm from "./FieldForm";
import Loader from "../ui/Loader";

class FieldsList extends Component {
  componentWillMount() {
  }

  render() {
    return (
        <div className="list">
          <div className="list-title">Поля</div>

          {this.renderBody()}
        </div>
    );
  }

  renderBody() {
    const { loading, entities } = this.props.fields;
    const { formVisible } = this.props;

    if (loading) {
      return <div className="list-body"><Loader /></div>;
    }

    if (formVisible) {
      return <FieldForm />
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

          <CreateButtonRow label="Создать параметр" onClick={this.onCreateClick.bind(this)} />
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
    fields: state.fields,
    formVisible: state.fieldForm.opened
  };
}

export default connect(mapStateToProps, { loadAll, showForm })(FieldsList);
