import React, { Component } from 'react';

import CurrencySwitcher from './CurrencySwitcher';

class ListItemProductHeader extends Component {
  render() {
    const { model } = this.props;

    return (
        <tr>
          <td className="product-row" colSpan={4}>
            <div className="product-extended-header">
              <a href={"/admin/products/update/" + model.id} target="_blank">{model.name}</a>
              <CurrencySwitcher model={model} />
            </div>
          </td>
        </tr>
    );
  }
}

export default ListItemProductHeader;