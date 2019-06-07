import React, { Component } from 'react';
import { connect } from 'react-redux';

import { move, quantityChange, remove } from "../ducks/list";
import ListRowModule from "./ListRowModule";
import ListRowModulePrice from "./ListRowModulePrice";
import ListRowQuantity from "./ListRowQuantity";

class ListRow extends Component {
  render() {
    const { model } = this.props;
    return (
        <tr>
          <td className="cell-name"><ListRowModule model={model} /></td>
          <td className="cell-price"><ListRowModulePrice model={model} /></td>
          <td className="cell-quantity"><ListRowQuantity onChange={this.onQuantityChange.bind(this)} model={model} />
          </td>
          <td className="cell-actions">
            <button className="grid-icon" onClick={this.onMoveUpClick.bind(this)}>
              <span className="glyphicon glyphicon-arrow-up" />
            </button>
            <button className="grid-icon" onClick={this.onMoveDownClick.bind(this)}>
              <span className="glyphicon glyphicon-arrow-down" />
            </button>
            <button className="grid-icon">
              <span className="glyphicon glyphicon-pencil" />
            </button>
            <button className="grid-icon" onClick={this.onRemoveClick.bind(this)}>
              <span className="glyphicon glyphicon-trash" />
            </button>
          </td>
        </tr>
    );
  }

  onMoveUpClick() {
    const { model } = this.props;

    this.props.move(model.id, 'up');
  }

  onMoveDownClick() {
    const { model } = this.props;

    this.props.move(model.id, 'down');
  }

  onRemoveClick() {
    const { model } = this.props;

    if (!window.confirm('Вы уверены, что хотите удалить?')) {
      return;
    }

    this.props.remove(model.id);
  }

  onQuantityChange(value) {
    const { model } = this.props;

    this.props.quantityChange(model.id, value);
  }
}

export default connect(null, { remove, move, quantityChange })(ListRow);