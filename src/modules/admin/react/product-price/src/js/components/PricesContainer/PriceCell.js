import React, { Component } from 'react';
import FormSelect from "../Form/FormSelect";

import { items } from "../../constants/DiscountMode";

class PriceCell extends Component {
  renderPriceDiscount() {
    const { onDiscountModeChange, onDiscountValueChange, discountMode, discountValue, id } = this.props;

    return (
        <div className="price-cell__mode">
          <div className="price-cell__mode-left">
            <FormSelect items={items()} id={id + '_mode'} value={discountMode}
                        onChange={value => onDiscountModeChange(value)} />
          </div>
          <div className="price-cell__mode-right">
            <input type="number" step="1.00" value={discountValue}
                   onChange={e => onDiscountValueChange(parseFloat(e.target.value))}
                   className="form-control" />
          </div>
        </div>
    );
  }

  render() {
    const { price, onPriceChange, onDiscountEnabledChange, id, hasDiscount } = this.props;

    return (
        <div className="price-cell">
          <div className="price-cell__top">
            <div className="price-cell__price">
              <input type="number" step="1.00"
                     value={price}
                     onChange={e => onPriceChange(parseFloat(e.target.value))}
                     className="form-control" />
            </div>

            <div className="price-cell__discount">
              <label htmlFor={id}>
                <input type="checkbox" id={id} checked={hasDiscount}
                       onChange={e => onDiscountEnabledChange(e.target.checked)} />&nbsp;Скидка</label>
            </div>
          </div>

          {hasDiscount ? this.renderPriceDiscount() : null}
        </div>
    );
  }
}

export default PriceCell;