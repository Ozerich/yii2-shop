import React, { Component } from 'react';
import { connect } from "react-redux";

class NewModuleFormCatalog extends Component {
  render() {
    const { close, value } = this.props;

    return <span>Товар из каталога</span>;
  }
}

function mapStateToProps(state) {
  return {};
}

export default connect(mapStateToProps)(NewModuleFormCatalog);
