import React, { Component } from 'react';
import { connect } from 'react-redux';

import { savePrice, toggleDiscount } from "../../ducks/params";
import PriceCell from "./PriceCell";
import StockCell from "./StockCell";

class PricesContainerDouble extends Component {
  render() {
    const { firstItems, secondItems } = this.props;

    return (
        <table className="prices-table prices-table--double">
          <tbody>
          <tr>
            <td>&nbsp;</td>
            {firstItems.map(model => <td>{model.model.name}</td>)}
          </tr>
          {secondItems.map(model => {
            return (
                <tr>
                  <td>{model.model.name}</td>
                  {firstItems.map(item => {
                    const key = item.serverId + 'x' + model.serverId;
                    return <td key={key}>
                      <PriceCell id={key}
                                 price={this.getPriceValue(item.serverId, model.serverId)}
                                 hasDiscount={this.hasDiscount(item.serverId, model.serverId)}
                                 discountMode={this.getDiscountMode(item.serverId, model.serverId)}
                                 discountValue={this.getDiscountValue(item.serverId, model.serverId)}
                                 onPriceChange={value => this.onPriceChange(item.serverId, model.serverId, { value })}
                                 onDiscountEnabledChange={value => this.onDiscountEnabledChange(item.serverId, model.serverId, value)}
                                 onDiscountModeChange={value => this.onPriceChange(item.serverId, model.serverId, { discount_mode: value })}
                                 onDiscountValueChange={value => this.onPriceChange(item.serverId, model.serverId, { discount_value: value })}
                      />
                      <StockCell id={key}
                                 stock={this.getStockValue(item.serverId, model.serverId)}
                                 days={this.getStockWaitingDays(item.serverId, model.serverId)}
                                 onStockChange={value => this.onPriceChange(item.serverId, model.serverId, { stock: value })}
                                 onStockDaysChange={value => this.onPriceChange(item.serverId, model.serverId, { stock_waiting_days: value })}
                      />
                    </td>;
                  })}
                </tr>
            );
          })}
          </tbody>
        </table>
    );
  }

  getModel(valueId, secondValudId) {
    const key = valueId + 'x' + secondValudId;
    const { prices } = this.props;

    return key in prices ? prices[key] : null;
  }

  getStockValue(valueId, secondValueId) {
    const model = this.getModel(valueId, secondValueId);
    return model ? model.stock : '';
  }

  getStockWaitingDays(valueId, secondValueId) {
    const model = this.getModel(valueId, secondValueId);
    return model ? model.stock_waiting_days : '';
  }

  getPriceValue(valueId, secondValueId) {
    const model = this.getModel(valueId, secondValueId);
    return model ? model.value : '';
  }

  getDiscountValue(valueId, secondValueId) {
    const model = this.getModel(valueId, secondValueId);
    return model ? model.discount_value : '';
  }

  getDiscountMode(valueId, secondValueId) {
    const model = this.getModel(valueId, secondValueId);
    return model ? model.discount_mode : '';
  }

  hasDiscount(valueId, secondValueId) {
    const model = this.getModel(valueId, secondValueId);
    return model ? model.has_discount : false;
  }

  onPriceChange(firstValueId, secondValueId, value) {
    const { productId, savePrice } = this.props;
    savePrice(productId, firstValueId, secondValueId, value);
  }

  onDiscountEnabledChange(firstValueId, secondValueId, value) {
    const { productId, toggleDiscount } = this.props;
    toggleDiscount(productId, firstValueId, secondValueId, value);
  }
}

function mapStateToProps(state) {
  return {
    productId: state.common.productId,
    prices: state.params.prices,
  };
}

export default connect(mapStateToProps, { savePrice, toggleDiscount })(PricesContainerDouble);