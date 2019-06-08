import React, { Component } from 'react';
import './styles.scss';
import CatalogSearchInput from "./CatalogSearchInput";
import CatalogSearchResults from "./CatalogSearchResults";

class CatalogSearch extends Component {
  constructor(props) {
    super(props);

    this.state = {
      entities: [],
      query: null,
      loading: false
    };
  }

  render() {
    return (
        <div>
          <CatalogSearchInput onChange={this.onSearchQueryChange.bind(this)} />
          {this.state.query ? <CatalogSearchResults onSelect={model => this.onSelect(model)} entities={this.state.entities} /> : null}
        </div>
    );
  }

  showLoading() {
    this.setState({
      loading: true
    });
  }

  hideLoading() {
    this.setState({
      loading: false
    });
  }

  setResults(results) {
    this.setState({
      loading: false,
      entities: results
    });
  }

  onSearchQueryChange(query) {
    this.setState({
      query
    });

    if (!this.props.searchFunc) {
      return;
    }

    this.showLoading();

    this.props.searchFunc(query).then(results => {
      this.setResults(results);
    }).catch(error => {

    });
  }

  onSelect(product){
    if(this.props.onSelect){
      this.props.onSelect(product);
    }
  }
}

export default CatalogSearch;