import React, { Component } from 'react';

class FormRadio extends Component {
  render() {
    const { id, label, selected, name } = this.props;

    return (
        <div className="form-group">
          <div>
            <label htmlFor={id}>
              <input type="radio" name={name} id={id} checked={selected} onChange={this.onChange.bind(this)} />
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

export default FormRadio;