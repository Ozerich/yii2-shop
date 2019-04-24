import React, { Component } from 'react';
import ListItemStock from "./ListItemStock";
import ListItemPriceDiscount from "./ListItemPriceDiscount";

class ListItem extends Component {
  render() {
    const { name, price, isChild, productId } = this.props;

    return (
        <tr className={isChild ? 'row-child' : ''}>
          <td className="cell-name">
            {isChild ? name : <a href={"/admin/products/update/" + productId} target="_blank">{name}</a>}
          </td>
          <td className="cell-price">
            <input type="text" className="price-input" value={price ? price.price : ''}
                   onChange={this.onPriceChange.bind(this)} />
          </td>
          <td className="cell-price">
            <ListItemPriceDiscount discountMode={price ? price.discount_mode : null}
                                   discountValue={price ? price.discount_value : null}
                                   onChange={this.onDiscountChange.bind(this)} />
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


  onDiscountChange(discountMode, discountValue) {
    if (this.props.onChange) {
      this.props.onChange(Object.assign(this.props.price || {}, {
        discount_mode: discountMode,
        discount_value: discountValue
      }));
    }
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