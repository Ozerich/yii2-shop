import React, { Component } from 'react';

class FormCheckbox extends Component {
  render() {
    const { id, label, value } = this.props;

    return (
        <div className="form-group">
          <div style={{ marginTop: '30px' }}>
            <label htmlFor={id}>
              <input type="checkbox" id={id} checked={value} onChange={this.onChange.bind(this)} />
              &nbsp;{label}
            </label>
          </div>
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