import React, { Component } from 'react';
import classNames from 'classnames';
import Loader from "./Loader";

class BlockWithLoader extends Component {
  render() {
    const { loading, children, className } = this.props;

    return (
        <div className={classNames(className, "loader-wrapper", { loading: loading })}>
          {loading ? <Loader /> : null}
          {children}
        </div>
    );
  }
}

export default BlockWithLoader;