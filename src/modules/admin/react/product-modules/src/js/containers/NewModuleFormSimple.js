import React, { Component } from 'react';
import { connect } from "react-redux";

class NewModuleFormSimple extends Component {
  render() {
    const { close, value } = this.props;

    return <span>Товар простой</span>;
  }
}

function mapStateToProps(state) {
  return {};
}

export default connect(mapStateToProps)(NewModuleFormSimple);
