import React, { Component } from 'react';
import ListItemPrice from "./ListItemPrice";
import ListItemStock from "./ListItemStock";

class ListItem extends Component {
  render() {
    const { name, price } = this.props;

    return (
        <tr>
          <td className="cell-name">
            {name}
          </td>
          <td className="cell-price">
            <ListItemPrice model={price} onChange={this.onPriceChange.bind(this)} />
          </td>
          <td className="cell-stock">
            <ListItemStock
                stock={price ? price.stock : null}
                waitingDays={price ? price.stock_waiting_days : null}
                onChange={this.onChangeStock.bind(this)}
            />
          </td>
        </tr>
    );
  }

  onChangeStock(stock, stockWaitingDays) {
    if (this.props.onChange) {
      this.props.onChange(Object.assign(this.props.price || {}, {
        stock: stock,
        stock_waiting_days: stockWaitingDays
      }));
    }
  }

  onPriceChange(price) {
    if (this.props.onChange) {
      this.props.onChange(Object.assign(this.props.price || {}, {
        price: price.price
      }));
    }
  }
}

export default ListItem;