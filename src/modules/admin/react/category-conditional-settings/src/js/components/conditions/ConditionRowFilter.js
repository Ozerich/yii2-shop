import React, { Component } from 'react';
import { connect } from 'react-redux';
import FormSelect from "../form/FormSelect";

import { changeFilter } from "../../ducks/conditions";

class ConditionRowFilter extends Component {
  getItems() {
    const items = [
      { id: 'PRICE', type: 'PRICE', label: 'Цена' },
      { id: 'CATEGORY', type: 'CATEGORY', label: 'Категория' },
      { id: 'MANUFACTURE', type: 'MANUFACTURE', label: 'Производитель' },
      { id: 'COLOR', type: 'COLOR', label: 'Цвет' },
    ];

    this.props.filters.filter(item => item.type !== 'STRING').forEach(item => items.push({
      id: item.id,
      type: 'FIELD',
      label: 'Параметр "' + item.name + '"'
    }));

    return items;
  }

  render() {
    const { value } = this.props;

    return <FormSelect items={this.getItems()} placeholder="Выберите фильтр" value={value}
                       onChange={this.handleChange.bind(this)} />;
  }

  handleChange(value) {
    const { model, changeFilter } = this.props;

    changeFilter(model.id, value);
  }
}

function mapStateToProps(state, ownProps) {
  return {
    value: state.conditions.items.find(item => item.id === ownProps.model.id).filter,
    filters: state.fields.items
  };
}

export default connect(mapStateToProps, { changeFilter })(ConditionRowFilter);