import React, { Component } from 'react';
import { connect } from 'react-redux';
import BlockOrLoader from "../components/ui/BlockOrLoader";
import ListView from "../components/list/ListView";

class List extends Component {
  render() {
    const { loading, visible } = this.props;

    if (!visible) {
      return visible;
    }

    return (
        <div className="box box-primary">
          <div className="box-body">
            <BlockOrLoader loading={loading}>
              <ListView />
            </BlockOrLoader>
          </div>
        </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    loading: state.list.loading,
    visible: state.list.visible
  }
}

export default connect(mapStateToProps)(List);