import React, { Component } from 'react';
import { connect } from 'react-redux';

import { savePrice, toggleDiscount } from "../../ducks/params";
import PriceCell from "./PriceCell";
import StockCell from "./StockCell";

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
            <th>Наличие</th>
          </tr>
          </thead>
          <tbody>
          {items.map(model => {
            const key = model.serverId;
            return (
                <tr>
                  <td>{model.model.name}</td>
                  <td>
                    <PriceCell id={key}
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
                  <td>
                    <StockCell id={key}
                               stock={this.getStockValue(model.serverId)}
                               days={this.getStockWaitingDays(model.serverId)}
                               onStockChange={value => this.onPriceChange(model.serverId, { stock: value })}
                               onStockDaysChange={value => this.onPriceChange(model.serverId, { stock_waiting_days: value })}
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

  getStockValue(valueId) {
    const model = this.getModel(valueId);
    return model ? model.stock : '';
  }

  getStockWaitingDays(valueId) {
    const model = this.getModel(valueId);
    return model ? model.stock_waiting_days : '';
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
    const { productId, savePrice, onChange } = this.props;
    onChange();
    savePrice(productId, firstValueId, null, value);
  }

  onDiscountEnabledChange(firstValueId, value) {
    const { productId, toggleDiscount, onChange } = this.props;
    onChange();
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