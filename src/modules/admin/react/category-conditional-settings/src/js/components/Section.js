import React, { Component } from 'react';
import BlockOrLoader from "./ui/BlockOrLoader";

class Section extends Component {
  render() {
    const { title, children, loading } = this.props;

    return (
        <div className="list">
          <div className="list-title">{title}</div>
          <div className="list-body">
            <BlockOrLoader loading={loading}>
              {children}
            </BlockOrLoader>
          </div>
        </div>
    );
  }
}

export default Section;