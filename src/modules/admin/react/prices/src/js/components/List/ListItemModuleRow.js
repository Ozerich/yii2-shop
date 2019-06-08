import React, { Component } from 'react';
import ListItemPriceDiscount from "./ListItemPriceDiscount";

class ListItemModuleRow extends Component {
  render() {
    const { model } = this.props;

    const { price } = model;

    if (model.is_catalog) {
      return (
          <tr className="row-child">
            <td className="cell-name">
              {model.name}
            </td>
            <td colSpan={3}>
              Товар из каталога
            </td>
          </tr>
      );
    }

    return (
        <tr className="row-child">
          <td className="cell-name">
            {model.name} - {model.sku}
          </td>
          <td className="cell-price">
            <div className="price-wrapper">
              <div className="price-input-wrapper">
                <input type="number" step="1.00" className="price-input"
                       onChange={this.onPriceChange.bind(this)}
                       value={model.price.price ? model.price.price : ''} />
              </div>
            </div>
          </td>
          <td className="cell-price" colSpan={2}>
            <ListItemPriceDiscount discountMode={price ? price.discount_mode : null}
                                   discountValue={price ? price.discount_value : null}
                                   onChange={this.onDiscountChange.bind(this)}
            />
          </td>
        </tr>
    );
  }

  onDiscountChange(discountMode, discountValue) {
    if (this.props.onChange) {
      this.props.onChange(Object.assign(this.props.model.price || {}, {
        discount_mode: discountMode,
        discount_value: discountValue
      }));
    }
  }

  onPriceChange(e) {
    if (this.props.onChange) {
      const value = parseFloat(e.target.value);
      this.props.onChange(Object.assign(this.props.model.price || {}, {
        price: value ? value : null
      }));
    }
  }
}

export default ListItemModuleRow;