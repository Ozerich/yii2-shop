import React, { Component } from 'react';
import { connect } from 'react-redux';

import { savePrice } from "../../ducks/params";

class PricesContainerDouble extends Component {
  render() {
    const { firstItems, secondItems } = this.props;

    return (
        <table className="prices-table">
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
                    return <td><input type="number"
                                      value={this.getPriceValue(model.serverId, item.serverId)}
                                      onChange={e => this.onPriceChange(model.serverId, item.serverId, e)}
                                      className="form-control" />
                    </td>;
                  })}
                </tr>
            );
          })}
          </tbody>
        </table>
    );
  }

  getPriceValue(valueId, secondValueId) {
    const key = valueId + 'x' + secondValueId;
    const { prices } = this.props;

    return key in prices ? prices[key] : '';
  }

  onPriceChange(firstValueId, secondValueId, e) {
    const { productId, savePrice } = this.props;

    savePrice(productId, firstValueId, secondValueId, parseInt(e.target.value));
  }
}

function mapStateToProps(state) {
  return {
    productId: state.common.productId,
    prices: state.params.prices,
  };
}

export default connect(mapStateToProps, { savePrice })(PricesContainerDouble);