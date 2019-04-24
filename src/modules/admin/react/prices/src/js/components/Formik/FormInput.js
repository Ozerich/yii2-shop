import React, { Component } from 'react';

class FormInput extends Component {
  render() {
    const { id, label, value, handleChange } = this.props;

    return (
        <div className="form-group field-updateproductform-name required">
          <label className="control-label" htmlFor={id}>{label}</label>
          <input type="text" id={id} className="form-control" value={value} onChange={handleChange} />
        </div>
    );
  }
}

export default FormInput;