import React, { Component } from 'react';
import './styles.scss';
import CatalogSearchResultsItem from "./CatalogSearchResultsItem";

class CatalogSearchResults extends Component {
  render() {
    const { entities } = this.props;

    return (
        <div className="search-results">
          {entities.length === 0 ? <div className="search-results__empty">Ничего не найдено</div> : (
              <div className="search-results__list">
                {entities.map(item => <CatalogSearchResultsItem onSelect={() => this.props.onSelect(item)}
                                                                model={item} key={item.id} />)}
              </div>
          )}
        </div>
    );
  }

}

export default CatalogSearchResults;