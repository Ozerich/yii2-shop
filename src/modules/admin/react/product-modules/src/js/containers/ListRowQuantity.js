import React, { Component } from 'react';
import classNames from 'classnames';

class ListRowQuantity extends Component {
  render() {
    const { model } = this.props;
    return (
        <div className="list-row__quantity">
          <button className={classNames({ disabled: model.quantity === 0 })} onClick={() => this.onChange(-1)}>-
          </button>
          <span>{model.quantity}</span>
          <button onClick={() => this.onChange(+1)}>+</button>
        </div>
    );
  }

  onChange(delta) {
    const { model } = this.props;

    if (model.quantity <= 0 && delta === -1) {
      return;
    }

    if (this.props.onChange) {
      this.props.onChange(model.quantity + delta);
    }
  }
}

export default ListRowQuantity;