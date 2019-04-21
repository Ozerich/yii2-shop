import React, { Component } from 'react';

class FormSelect extends Component {
  render() {
    const { id, label, value, items, name } = this.props;

    return (
        <div className="form-group field-updateproductform-name required">
          {label ? <label className="control-label" htmlFor={id}>{label}</label> : null}
          <select className="form-control" name={name || id} onChange={this.onChange.bind(this)}>
            {items.map(item => {
              return <option key={item.id} value={item.id} selected={item.id === value}>{item.label}</option>
            })}
          </select>
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

export default FormSelect;