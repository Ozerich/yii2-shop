import React, { Component } from 'react';

class ListItemProductHeader extends Component {
  render() {
    const { model } = this.props;

    return (
        <tr>
          <td className="product-row" colSpan={3}>#{model.id} - {model.name}</td>
        </tr>
    );
  }
}

export default ListItemProductHeader;