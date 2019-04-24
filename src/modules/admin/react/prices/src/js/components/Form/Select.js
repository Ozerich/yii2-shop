import React, { Component } from 'react';

class Select extends Component {
  render() {
    const { options, value } = this.props;

    if (!options) {
      return null;
    }

    return (
        <select  onChange={this.onChange.bind(this)} value={value}>
          {options.map(item => <option key={item.id} value={item.id}>{item.label}</option>)}
        </select>
    );
  }

  onChange(e) {
    if (this.props.onChange) {
      this.props.onChange(e.target.value);
    }
  }
}

export default Select;