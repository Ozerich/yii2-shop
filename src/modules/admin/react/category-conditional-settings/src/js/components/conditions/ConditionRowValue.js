import React, { Component } from 'react';
import { connect } from 'react-redux';
import FormInput from "../form/FormInput";
import FormCheckbox from "../form/FormCheckbox";
import FormSelect from "../form/FormSelect";

import { changeValue } from "../../ducks/conditions";

class ConditionRowValue extends Component {

  renderNumberInput() {
    const { value } = this.props;

    return <FormInput type="number" value={value} onChange={this.handleChange.bind(this)} />;
  }

  renderCheckbox() {
    const { value } = this.props;

    return <FormCheckbox value={value} onChange={this.handleChange.bind(this)} />;
  }

  renderSelect(items, isMultiple) {
    const { value } = this.props;

    return <FormSelect items={items.map(item => {
      return { id: item, label: item }
    })} value={value} multiple={isMultiple} onChange={this.handleChange.bind(this)} />
  }

  render() {
    const { model } = this.props;

    if (!model.filter || !model.compare) {
      return null;
    }

    const { filter, compare } = this.props.model;

    if (filter === 'PRICE') {
      return this.renderNumberInput();
    }

    const filterModel = this.props.filters.find(item => item.id === +filter);

    if (!filterModel) {
      return [];
    }

    const { type } = filterModel;

    switch (type) {
      case 'INTEGER':
        return this.renderNumberInput();
      case 'BOOLEAN':
        return this.renderCheckbox();
      case 'SELECT':
        return this.renderSelect(filterModel.values, compare === 'ONE');

      default:
        return null;
    }
  }

  handleChange(value) {
    const { model, changeValue } = this.props;

    changeValue(model.id, value);
  }
}

function mapStateToProps(state, ownProps) {
  return {
    value: state.conditions.items.find(item => item.id === ownProps.model.id).value,
    filters: state.fields.items
  };
}

export default connect(mapStateToProps, { changeValue })(ConditionRowValue);