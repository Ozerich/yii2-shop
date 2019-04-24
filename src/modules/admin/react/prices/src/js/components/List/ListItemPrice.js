import React, { Component } from 'react';

import ListItemPriceDiscount from './ListItemPriceDiscount';

class ListItemPrice extends Component {
  render() {
    const { model } = this.props;

    return (
        <div className="price">
          <input type="text" className="price-input" value={model ? model.price : ''} onChange={this.onPriceChange.bind(this)} />
          <ListItemPriceDiscount discountMode={model ? model.discount_mode : null}
                                 discountValue={model ? model.discount_value : null}
                                 onChange={this.onDiscountChange.bind(this)} />
        </div>
    );
  }

  onDiscountChange(discountMode, discountValue) {
    this.props.onChange(Object.assign(this.props.model, {
      discount_mode: discountMode,
      discount_value: discountValue
    }));
  }

  onPriceChange(e) {
    this.props.onChange(Object.assign(this.props.model, {
      price: +e.target.value
    }));
  }
}

export default ListItemPrice;