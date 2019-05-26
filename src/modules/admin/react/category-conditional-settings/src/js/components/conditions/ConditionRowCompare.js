import React, { Component } from 'react';
import { connect } from 'react-redux';
import { changeCompare } from "../../ducks/conditions";

import FormSelect from "../form/FormSelect";

class ConditionRowCompare extends Component {
  getItems() {
    const { filter } = this.props.model;

    if (filter === 'PRICE') {
      return [
        { id: 'LESS', label: 'Меньше чем' },
        { id: 'MORE', label: 'Больше чем' },
        { id: 'EQUAL', label: 'Равно' }
      ];
    }

    if (filter === 'CATEGORY') {
      return [
        { id: 'ONE', label: 'Одна из' }
      ];
    }

    if (filter === 'MANUFACTURE') {
      return [
        { id: 'EQUAL', label: 'Равно' }
      ];
    }

    if (filter === 'COLOR') {
      return [
        { id: 'EQUAL', label: 'Равно' },
        { id: 'ONE', label: 'Одна из' }
      ];
    }

    const filterModel = this.props.filters.find(item => item.id === +filter);

    if (!filterModel) {
      return [];
    }

    const { type } = filterModel;

    switch (type) {
      case 'INTEGER':
        return [
          { id: 'LESS', label: 'Меньше чем' },
          { id: 'MORE', label: 'Больше чем' },
          { id: 'EQUAL', label: 'Равно' },
          { id: 'NOT_EQUAL', label: 'Не равно' }
        ];
      case 'BOOLEAN':
        return [
          { id: 'EQUAL', label: 'Равно' },
          { id: 'NOT_EQUAL', label: 'Не равно' }
        ];
      case 'SELECT':
        return [
          { id: 'EQUAL', label: 'Равно' },
          { id: 'NOT_EQUAL', label: 'Не равно' },
          { id: 'ONE', label: 'Один из' },
        ];
      default:
        return [];
    }
  }

  render() {
    const { model } = this.props;

    if (!model.filter) {
      return '';
    }

    return <FormSelect items={this.getItems()} placeholder="Выберите условие" value={model.compare}
                       onChange={this.handleChange.bind(this)} />;
  }

  handleChange(value) {
    const { model, changeCompare } = this.props;

    changeCompare(model.id, value);
  }

}


function mapStateToProps(state, ownProps) {
  return {
    value: state.conditions.items.find(item => item.id === ownProps.model.id).compare,
    filters: state.fields.items
  };
}

export default connect(mapStateToProps, { changeCompare })(ConditionRowCompare);