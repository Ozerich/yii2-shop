import React, { Component } from 'react';
import FormSelect from "../Form/FormSelect";

import { items, NO, WAITING } from "../../constants/Stock";

class StockCell extends Component {

  render() {
    const { id, stock, onStockChange, days, onStockDaysChange } = this.props;

    return (
        <div className="stock-cell">
          <FormSelect items={items()} id={id + '_stock'} value={stock ? stock : NO}
                      onChange={value => onStockChange(value)} />
          {stock === WAITING ? <div className="form-group">
            <label className="control-label" htmlFor={id + '_stock_days'}>макс. кол-во дней</label>
            <input type="text" id={id + '_stock_days'} value={days} className="form-control"
                   onChange={e => onStockDaysChange(parseInt(e.target.value))} />
          </div> : null}
        </div>
    );
  }
}

export default StockCell;