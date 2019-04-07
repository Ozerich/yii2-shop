import React, { Component } from 'react';

function getSelectValues(select) {
  const result = [];
  const options = select && select.options;
  let opt;

  for (let i = 0, iLen = options.length; i < iLen; i++) {
    opt = options[i];

    if (opt.selected) {
      result.push(opt.value || opt.text);
    }
  }
  return result;
}

class FormSelect extends Component {
  getItems() {
    const { items, value, placeholder } = this.props;

    if (!placeholder || value) {
      return items;
    }

    return [{ id: null, label: placeholder }, ...items];
  }

  render() {
    const { id, value, name, multiple } = this.props;

    return (
        <div className="form-group">
          <select className="form-control" name={name || id} onChange={this.onChange.bind(this)} multiple={multiple}>
            {this.getItems().map(item => {
              const selected = Array.isArray(value) ? value.indexOf(item.id) !== -1 : item.id === value;
              return <option key={item.id} value={item.id} selected={selected}>{item.label}</option>
            })}
          </select>
        </div>
    );
  }

  onChange(e) {
    if (this.props.onChange) {
      this.props.onChange(this.props.multiple ? getSelectValues(e.target) : e.target.value);
    }
  }
}

export default FormSelect;