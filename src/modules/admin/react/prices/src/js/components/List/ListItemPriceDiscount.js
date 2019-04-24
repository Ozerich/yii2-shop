import React, { Component } from 'react';
import Select from "../Form/Select";
import { FIXED, items } from "../../constants/DiscountMode";
import Checkbox from "../Form/Checkbox";
import NumberInput from "../Form/NumberInput";

class ListItemPriceDiscount extends Component {
  render() {
    const { discountMode, discountValue } = this.props;

    return (
        <div className="price-discount">
          <Checkbox label="Скидка" checked={!!discountMode} onChange={this.onEnabledChange.bind(this)} />

          {discountMode ? (
              <>
              <Select options={items()} value={discountMode} onChange={this.onDiscountModeChange.bind(this)} />
              <NumberInput value={discountValue} onChange={this.onDiscountValueChange.bind(this)} />
              </>
          ) : null}
        </div>
    );
  }

  onDiscountValueChange(value) {
    this.props.onChange(this.props.discountMode, parseInt(value));
  }

  onDiscountModeChange(value) {
    this.props.onChange(value, this.props.discountValue);
  }

  onEnabledChange(isEnabled) {
    if (isEnabled) {
      this.props.onChange(FIXED, 0);
    } else {
      this.props.onChange(null, null);
    }
  }
}

export default ListItemPriceDiscount;