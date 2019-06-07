import React, { Component } from 'react';
import Loader from "./Loader";

class BlockOrLoader extends Component {
  render() {
    const { loading, children } = this.props;

    return (
        <div style={{ minHeight: (loading ? '100px' : null), position: 'relative' }}>
          {loading ? <Loader /> : children}
        </div>
    );
  }
}

export default BlockOrLoader;