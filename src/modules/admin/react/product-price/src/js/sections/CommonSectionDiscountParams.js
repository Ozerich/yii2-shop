import React, { Component } from 'react';

import FormInput from '../components/Form/FormInput';

class CommonSectionDiscountParams extends Component {

  renderSummary(value) {
    return (
        <div className="col-xs-3">
          <label className="control-label">Цена со скидкой</label>
          <div style={{ paddingTop: '8px' }}>{Math.ceil(value.toFixed(2))}</div>
        </div>
    );
  }

  render() {
    const { mode, values, handleChange } = this.props;

    switch (mode) {

      case 'FIXED':
        return (
            <div className="row">
              <div className="col-xs-3">
                <FormInput id="discountValue" label="Цена со скидкой"
                           handleChange={handleChange}
                           value={values.discountValue} />
              </div>
            </div>
        );

      case 'AMOUNT':
        return (
            <div className="row">
              <div className="col-xs-3">
                <FormInput id="discountValue" label="Размер скидки"
                           handleChange={handleChange}
                           value={values.discountValue} />
              </div>

              {this.renderSummary(values.price - values.discountValue)}
            </div>
        );


      case 'PERCENT':
        return (
            <div className="row">
              <div className="col-xs-3">
                <FormInput id="discountValue" label="Размер скидки в %"
                           handleChange={handleChange}
                           value={values.discountValue} />
              </div>
              {this.renderSummary(values.price - (values.price / 100) * values.discountValue)}
            </div>
        );

      default:
        return null;
    }
  }
}

export default CommonSectionDiscountParams;