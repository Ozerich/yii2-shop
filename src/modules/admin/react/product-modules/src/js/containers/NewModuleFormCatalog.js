import React, { Component } from 'react';
import { connect } from "react-redux";
import CommonService from '../services/common';
import CatalogSearch from "../components/form/CatalogSearch/CatalogSearch";

import { resetCatalogProduct, selectCatalogProduct } from "../ducks/new";
import CatalogSearchSelected from "../components/form/CatalogSearch/CatalogSearchSelected";

const service = new CommonService;

class NewModuleFormCatalog extends Component {
  render() {
    if (this.props.selected) {
      return <CatalogSearchSelected onCancel={() => this.onCancel()} model={this.props.selected} />
    } else {
      return <CatalogSearch searchFunc={query => service.search(query)} onSelect={this.onSelect.bind(this)} />;
    }
  }

  onSelect(product) {
    this.props.selectCatalogProduct(product);
  }

  onCancel() {
    this.props.resetCatalogProduct();
  }
}

function mapStateToProps(state) {
  return {
    selected: state.new.catalogSelectedProduct
  };
}

export default connect(mapStateToProps, { selectCatalogProduct, resetCatalogProduct })(NewModuleFormCatalog);
