import React, { Component } from 'react';
import './styles.scss';
import Button from "../../buttons/Button";

class CatalogSearchResultsItem extends Component {
  render() {
    const { model } = this.props;

    return (
        <div className="search-results__item">

          <div className="search-results__item-image">
            <img src={model.image.small} />
          </div>

          <div className="search-results__item-name">
            {model.name}
          </div>

          <div className="search-results__item-select">
            <Button onClick={() => this.props.onSelect()}>Выбрать</Button>
          </div>
        </div>
    );
  }

}

export default CatalogSearchResultsItem;