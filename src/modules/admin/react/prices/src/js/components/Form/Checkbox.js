import React, { Component } from 'react';

class Checkbox extends Component {
  render() {
    const { id, label, checked } = this.props;

    return (
        <div className="form-group">
          <label htmlFor={id}>
            <input type="checkbox" id={id} checked={checked} onChange={this.onChange.bind(this)} />
            &nbsp;{label}
          </label>
        </div>
    );
  }

  onChange(e) {
    if (this.props.onChange) {
      this.props.onChange(e.target.checked);
    }
  }
}

export default Checkbox;