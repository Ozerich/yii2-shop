import React, { Component } from 'react';

class FormSelect extends Component {
  render() {
    const { id, label, value, items, name } = this.props;

    return (
        <div className="form-group field-updateproductform-name required">
          <label className="control-label" htmlFor={id}>{label}</label>
          <select className="form-control" name={name || id} onChange={this.onChange.bind(this)}>
            {items.map(item => {
              return <option key={item.id} value={item.id} selected={item.id === value}>{item.label}</option>
            })}
          </select>
        </div>
    );
  }

  /*
  render() {
    const { id, label, value, noMargin } = this.props;

    return (
        <div className="form-group">
          <div style={noMargin ? null : { marginTop: '30px' }}>
            <label htmlFor={id}>
              <input type="checkbox" id={id} checked={value} onChange={this.onChange.bind(this)} />
              &nbsp;{label}
            </label>
          </div>
        </div>
    );
  }*/

  onChange(e) {
    if (this.props.handleChange) {
      this.props.handleChange(e);
    }
    if (this.props.onChange) {
      this.props.onChange(e.target.checked);
    }
  }
}

export default FormSelect;