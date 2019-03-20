import React, { Component } from 'react';

class FormInput extends Component {
  render() {
    const { id, label, value, handleChange, items, name } = this.props;

    return (
        <div className="form-group field-updateproductform-name required">
          <label className="control-label" htmlFor={id}>{label}</label>
          <select className="form-control" name={name || id} onChange={handleChange}>
            {Object.keys(items).map(itemId => {
              return <option value={itemId} selected={itemId === value}>{items[itemId]}</option>
            })}
          </select>
        </div>
    );
  }
}

export default FormInput;