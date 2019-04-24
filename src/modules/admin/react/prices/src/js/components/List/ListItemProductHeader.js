import React, { Component } from 'react';

class ListItemProductHeader extends Component {
  render() {
    const { model } = this.props;

    return (
        <tr>
          <td className="product-row" colSpan={4}>
            <a href={"/admin/products/update/" + model.id} target="_blank">{model.name}</a>
          </td>
        </tr>
    );
  }
}

export default ListItemProductHeader;