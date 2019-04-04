import React, { Component } from 'react';
import { connect } from 'react-redux';
import BlockOrLoader from "../ui/BlockOrLoader";
import ActiveFieldsRow from './ActiveFieldsRow';

import { load } from '../../ducks/active';

class ActiveFields extends Component {
  componentWillMount() {
    this.props.load();
  }

  render() {
    const { loading, items } = this.props;

    if (!loading && items.length === 0) {
      return null;
    }

    return (
        <div className="list">
          <div className="list-title">Активные поля</div>
          <div className="list-body">
            <BlockOrLoader loading={loading}>
              {items.map(item => <ActiveFieldsRow key={item.field_id} model={item} />)}
            </BlockOrLoader>
          </div>
        </div>
    );
  }
}

function mapStateToProps(state) {
  return state.active;
}

export default connect(mapStateToProps, { load })(ActiveFields);