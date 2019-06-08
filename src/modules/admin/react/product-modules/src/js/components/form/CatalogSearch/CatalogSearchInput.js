import React, { Component } from 'react';
import './styles.scss';

class CatalogSearchInput extends Component {
  constructor(props) {
    super(props);

    this.state = {
      entities: []
    };

    this.timer = null;
  }

  render() {
    return (
        <div>
          <label className="form-label">Введите название товара</label>
          <input type="text" className="form-control" onChange={this.onChange.bind(this)} />
        </div>
    );
  }

  onChange(e) {
    const value = e.target.value.trim();

    if (this.timer) {
      clearTimeout(this.timer);
    }

    this.timer = setTimeout(() => {
      if (this.props.onChange) {
        this.props.onChange(value);
      }
    }, 200);
  }
}

export default CatalogSearchInput;