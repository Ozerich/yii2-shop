import React, { Component } from 'react';

class ListRowModulePrice extends Component {
  render() {
    const { model } = this.props;

    return (
        <div className="list-row__price">
          {model.price_with_discount ? (
              <div>
                <span className="list-row__price-old">{model.price} руб.</span>
                <span className="list-row__price-value">{model.price_with_discount} руб.</span>
              </div>
          ) : <span className="list-row__price-value">{model.price} руб.</span>}
        </div>
    );
  }
}

export default ListRowModulePrice;