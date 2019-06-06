import React, { Component } from 'react';

class FormCheckbox extends Component {
  render() {
    const { id, label, value,defaultValue } = this.props;

    return (
        <div>
          <label htmlFor={id}>
            <input type="checkbox" id={id} defaultChecked={defaultValue} checked={value} onChange={this.onChange.bind(this)} />
            &nbsp;{label}
          </label>
        </div>
    );
  }

  onChange(e) {
    if (this.props.handleChange) {
      this.props.handleChange(e);
    }
    if (this.props.onChange) {
      this.props.onChange(e.target.checked);
    }
  }
}

export default FormCheckbox;