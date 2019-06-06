import React, { Component } from 'react';
import {connect} from 'react-redux';
import FormCheckbox from "../Formik/FormCheckbox";

import {update} from "../../ducks/list";

class ListViewRowField extends Component {
  constructor(props) {
    super(props);

    this.value = this.getValue();
  }

  getValue() {
    const { model, field } = this.props;

    for (let i = 0; i < model.fields.length; i++) {
      if (model.fields[i].id === field.id) {
        if (field.type === 'SELECT' && field.multiple) {
          return model.fields[i].value ? model.fields[i].value.split(';') : [];
        }
        return model.fields[i].value;
      }
    }

    return null;
  }

  renderBoolean() {
    return <input type="checkbox" defaultChecked={this.getValue()} onChange={e => this.onChange(e.target.checked)} />
  }

  renderSelect() {
    const { field } = this.props;

    return (
        <select defaultValue={this.getValue()} onChange={e => this.onChange(e.target.value)}>
          <option value={''}></option>
          {field.values.map(item => <option>{item}</option>)}
        </select>
    );
  }

  renderMultipleSelect() {
    const { field } = this.props;

    return field.values.map(item => {
      const checked = this.value ? this.value.indexOf(item) !== -1 : false;
      return <FormCheckbox label={item} defaultValue={checked} onChange={checked => {
        if (checked) {
          this.value.push(item);
        } else {
          this.value = this.value.filter(i => i !== item);
        }
        this.onChange(this.value);
      }} />;
    });
  }

  renderInteger() {
    return <input type="text" defaultValue={this.getValue()} onChange={e => this.onChange(e.target.value)} />
  }

  render() {
    const { field } = this.props;

    switch (field.type) {
      case 'BOOLEAN':
        return this.renderBoolean();
      case 'SELECT':
        return field.multiple ? this.renderMultipleSelect() : this.renderSelect();
      case 'INTEGER':
        return this.renderInteger();
      default:
        return this.getValue();
    }
  }

  onChange(value) {
    const { field, model } = this.props;

    this.props.update(model.id, field.id, value);
  }
}

export default connect(null, {update})(ListViewRowField);