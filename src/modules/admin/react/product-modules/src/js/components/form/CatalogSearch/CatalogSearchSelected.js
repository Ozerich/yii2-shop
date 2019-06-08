import React, { Component } from 'react';
import './styles.scss';
import RedButton from "../../buttons/RedButton";

class CatalogSearchSelected extends Component {
  render() {
    const { model } = this.props;

    return (
        <div className="catalog-search__selected">
          <span className="catalog-search__selected-name">{model.name}</span>

          <RedButton onClick={() => this.props.onCancel()}>Отменить</RedButton>
        </div>
    );
  }
}

export default CatalogSearchSelected;