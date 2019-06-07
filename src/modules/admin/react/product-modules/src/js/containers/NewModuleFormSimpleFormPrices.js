import React, { Component } from 'react';
import FormInput from "../components/form/FormInput";
import FormSelect from "../components/form/FormSelect";

import { DISCOUNT_MODE_MONEY, DISCOUNT_MODE_PERCENT, DISCOUNT_MODE_VALUE, list } from "../constants/DiscountMode";

class NewModuleFormSimpleFormPrices extends Component {
  render() {
    const { handleChange, values } = this.props;

    return (
        <div className="row">
          <div className="col-xs-3">
            <FormInput label="Цена" id="price" value={values.price} handleChange={handleChange} />
          </div>

          <div className="col-xs-3">
            <FormSelect label="Скидка" id="discount" items={list()} emptyValue="Без скидки" value={values.discount}
                        handleChange={handleChange} />
          </div>

          {values.discount ? (
              <>
                <div className="col-xs-3">
                  <FormInput label={this.getDiscountValueLabel()} id="discount_value" value={values.discount_value}
                             handleChange={handleChange} />
                </div>

                <div className="col-xs-3">

                </div>
              </>
          ) : null}
        </div>
    );
  }

  getDiscountValueLabel() {
    const { values } = this.props;

    switch (values.discount) {
      case DISCOUNT_MODE_PERCENT:
        return 'Размер скидки, %';
      case DISCOUNT_MODE_MONEY:
        return 'Размер скидки, руб.';
      case DISCOUNT_MODE_VALUE:
        return 'Цена со скидкой';
      default:
        return null;
    }
  }
}

export default NewModuleFormSimpleFormPrices;
