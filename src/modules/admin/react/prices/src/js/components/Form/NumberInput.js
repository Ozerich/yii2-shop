import React, { Component } from 'react';

class NumberInput extends Component {
  render() {
    const { id, label, value, disabled, className } = this.props;

    return (
        <div className="form-group">
          {label ?
              <label className="control-label" htmlFor={id}>{label}</label> : null}
          <input type="text" id={id} disabled={disabled} className={className !== null ? className : "form-control"}
                 value={value}
                 onChange={this.onChange.bind(this)} />
        </div>
    );
  }

  onChange(e) {
    if (this.props.handleChange) {
      this.props.handleChange(e);
    }

    if (this.props.onChange) {
      this.props.onChange(e.target.value);
    }
  }
}

export default NumberInput;