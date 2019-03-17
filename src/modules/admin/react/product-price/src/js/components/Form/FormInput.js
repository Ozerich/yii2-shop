import React, { Component } from 'react';

class FormInput extends Component {
  render() {
    const { id, label, value } = this.props;

    return (
        <div className="form-group field-updateproductform-name required">
          <label className="control-label" htmlFor={id}>{label}</label>
          <input type="text" id={id} className="form-control" value={value} onChange={this.onChange.bind(this)} />
        </div>
    );
  }

  onChange(e) {
    if (this.props.onChange) {
      this.props.onChange(e.target.value);
    }
  }
}

export default FormInput;