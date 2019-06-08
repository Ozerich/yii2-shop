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
                       value={model.price.price ? model.price.price : ''} />
              </div>
            </div>
          </td>
          <td className="cell-price" colSpan={2}>
            <ListItemPriceDiscount discountMode={price ? price.discount_mode : null}
                                   discountValue={price ? price.discount_value : null} />
          </td>
        </tr>
    );
  }
}

export default ListItemModuleRow;