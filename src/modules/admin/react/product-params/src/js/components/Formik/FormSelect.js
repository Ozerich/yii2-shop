import React, { Component } from 'react';

class FormSelect extends Component {
  getOptions() {
    const { items, emptyValue } = this.props;
    let result = [];

    if (Array.isArray(items)) {
      result = items;
    } else {
      result = Object.keys(items).map(item => {
        return {
          id: item,
          label: items[item]
        }
      });
    }

    result = [{
      id: '',
      label: emptyValue
    }, ...result];

    return result;
  }

  render() {
    const { id, label, value, handleChange, items, name } = this.props;

    return (
        <div className="form-group">
          <label className="control-label" htmlFor={id}>{label}</label>
          <select className="form-control" name={name || id} onChange={handleChange}>
            {this.getOptions().map(item => {
              return <option key={item.id} value={item.id} selected={item.id === value}>{item.label}</option>
            })}
          </select>
        </div>
    );
  }
}

export default FormSelect;