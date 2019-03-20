import React, { Component } from 'react';

class FormArea extends Component {
  render() {
    const { id, label, name, value, handleChange } = this.props;

    return (
        <div className="form-group field-updateproductform-name required">
          <label className="control-label" htmlFor={id}>{label}</label>
          <textarea name={name} style={{ height: '300px' }} id={id} className="form-control"
                    value={Array.isArray(value) ? value.join("\n") : value} onChange={handleChange} />
        </div>
    );
  }
}

export default FormArea;