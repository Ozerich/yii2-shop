import React, { Component } from 'react';
import ListItemStock from "./ListItemStock";
import ListItemPriceDiscount from "./ListItemPriceDiscount";
import CurrencySwitcher from './CurrencySwitcher';

class ListItem extends Component {
  render() {
    const { name, price, isChild, productId, model } = this.props;

    return (
        <tr className={isChild ? 'row-child' : ''}>
          <td className="cell-name">
            {isChild ? name : <a href={"/admin/products/update/" + productId} target="_blank">{name}</a>}
          </td>
          <td className="cell-price">
            <div className="price-wrapper">
              <div className="price-input-wrapper">
                <input type="number" step="1.00" className="price-input" value={price ? price.price : ''}
                       onChange={this.onPriceChange.bind(this)} />
              </div>

              {isChild ? null : <CurrencySwitcher model={model} />}
            </div>
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

  onPriceChange(e) {
    if (this.props.onChange) {
      const value = parseFloat(e.target.value);
      this.props.onChange(Object.assign(this.props.price || {}, {
        price: value ? value : null
      }));
    }
  }
}

export default ListItem;