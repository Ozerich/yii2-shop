import React, { Component } from 'react';
import { connect } from 'react-redux';

import { savePrice, toggleDiscount } from "../../ducks/params";
import PriceCell from "./PriceCell";

class PricesContainerSingle extends Component {
  render() {
    const { model, items } = this.props;

    return (
        <table className="prices-table">
          <caption>{model.name}</caption>
          <thead>
          <tr>
            <th>Название</th>
            <th>Цена</th>
          </tr>
          </thead>
          <tbody>
          {items.map(model => {
            const key = model.serverId;
            return (
                <tr>
                  <td>{model.model.name}</td>
                  <td><PriceCell id={key}
                                 price={this.getPriceValue(model.serverId)}
                                 hasDiscount={this.hasDiscount(model.serverId)}
                                 discountMode={this.getDiscountMode(model.serverId)}
                                 discountValue={this.getDiscountValue(model.serverId)}
                                 onPriceChange={value => this.onPriceChange(model.serverId, { value })}
                                 onDiscountEnabledChange={value => this.onDiscountEnabledChange(model.serverId, value)}
                                 onDiscountModeChange={value => this.onPriceChange(model.serverId, { discount_mode: value })}
                                 onDiscountValueChange={value => this.onPriceChange(model.serverId, { discount_value: value })}
                  />
                  </td>
                </tr>
            );
          })}
          </tbody>
        </table>
    );
  }

  getModel(valueId) {
    const { prices } = this.props;
    return valueId in prices ? prices[valueId] : null;
  }

  getPriceValue(valueId) {
    const model = this.getModel(valueId);
    return model ? model.value : '';
  }

  getDiscountValue(valueId,) {
    const model = this.getModel(valueId);
    return model ? model.discount_value : '';
  }

  getDiscountMode(valueId) {
    const model = this.getModel(valueId);
    return model ? model.discount_mode : '';
  }

  hasDiscount(valueId) {
    const model = this.getModel(valueId);
    return model ? model.has_discount : false;
  }

  onPriceChange(firstValueId, value) {
    const { productId, savePrice } = this.props;
    savePrice(productId, firstValueId, null, value);
  }

  onDiscountEnabledChange(firstValueId, value) {
    const { productId, toggleDiscount } = this.props;
    toggleDiscount(productId, firstValueId, null, value);
  }
}

function mapStateToProps(state, ownProps) {
  return {
    productId: state.common.productId,
    paramId: ownProps.model.id,
    prices: state.params.prices,
  };
}

export default connect(mapStateToProps, { savePrice, toggleDiscount })(PricesContainerSingle);