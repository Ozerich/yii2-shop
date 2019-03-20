import React, { Component } from 'react';

class FormInput extends Component {
  render() {
    const { id, label, value, handleChange, items, name } = this.props;

    return (
        <div className="form-group field-updateproductform-name required">
          <label className="control-label" htmlFor={id}>{label}</label>
          <select className="form-control" name={name || id} onChange={handleChange}>
            {items.map(item => {
              return <option key={item.id} value={item.id} selected={item.id === value}>{item.label}</option>
            })}
          </select>
        </div>
    );
  }
}

export default FormInput;