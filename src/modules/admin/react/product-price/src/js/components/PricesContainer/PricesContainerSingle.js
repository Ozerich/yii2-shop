import React, { Component } from 'react';
import { connect } from 'react-redux';

import { savePrice } from "../../ducks/params";

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

            return (
                <tr>
                  <td>{model.model.name}</td>
                  <td>
                    <input type="number" className="form-control" value={this.getPriceValue(model.serverId)}
                           onChange={e => this.onPriceChange(model.serverId, e)} />
                  </td>
                </tr>
            );
          })}
          </tbody>
        </table>
    );
  }

  getPriceValue(valueId) {
    const { prices } = this.props;

    return valueId in prices ? prices[valueId] : '';
  }

  onPriceChange(valueId, e) {
    const { productId, savePrice } = this.props;

    savePrice(productId, valueId, null, parseInt(e.target.value));
  }
}

function mapStateToProps(state, ownProps) {
  return {
    productId: state.common.productId,
    paramId: ownProps.model.id,
    prices: state.params.prices,
  };
}

export default connect(mapStateToProps, { savePrice })(PricesContainerSingle);