import React, { Component } from 'react';
import FormSelect from "../Form/FormSelect";

import { items } from "../../constants/DiscountMode";

class PriceCell extends Component {
  renderPriceDiscount() {
    const { onDiscountModeChange,onDiscountValueChange, discountMode, discountValue, id } = this.props;

    return (
        <div className="price-cell__mode">
          <FormSelect items={items()} id={id + '_mode'} value={discountMode}
                      onChange={value => onDiscountModeChange(value)} />
          <input type="number" value={discountValue} onChange={e => onDiscountValueChange(parseInt(e.target.value))}
                 className="form-control" />
        </div>
    );
  }

  render() {
    const { price, onPriceChange, onDiscountEnabledChange, id, hasDiscount } = this.props;

    return (
        <div className="price-cell">
          <div className="price-cell__price">
            <input type="number"
                   value={price}
                   onChange={e => onPriceChange(parseInt(e.target.value))}
                   className="form-control" />
          </div>

          <div className="price-cell__discount">
            <label htmlFor={id}>
              <input type="checkbox" id={id} checked={hasDiscount}
                     onChange={e => onDiscountEnabledChange(e.target.checked)} />&nbsp;Скидка</label>
          </div>

          {hasDiscount ? this.renderPriceDiscount() : null}
        </div>
    );
  }
}

export default PriceCell;