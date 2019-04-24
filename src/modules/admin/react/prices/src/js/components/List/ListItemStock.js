import React, { Component } from 'react';

import { items, NO, WAITING } from '../../constants/Stock';
import Select from "../Form/Select";
import NumberInput from "../Form/NumberInput";

class ListItemStock extends Component {
  render() {
    const { stock, waitingDays } = this.props;

    return (
        <div className="stock">
          <Select options={items()} value={stock ? stock : NO} onChange={this.onChangeStock.bind(this)} />
          {stock === WAITING ? <NumberInput label="Количество дней" className="" value={waitingDays}
                                            onChange={this.onChangeWaitingDays.bind(this)} /> : null}
        </div>
    );
  }

  onChangeStock(value) {
    this.props.onChange(value, this.props.waitingDays);
  }

  onChangeWaitingDays(value) {
    this.props.onChange(this.props.stock, +value);
  }
}

export default ListItemStock;