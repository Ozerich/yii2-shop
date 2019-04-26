import React, { Component } from 'react';
import { connect } from 'react-redux';

import ListItem from "../components/List/ListItem";
import BlockOrLoader from '../components/ui/BlockOrLoader';

class List extends Component {
  render() {
    const { loading, items } = this.props;

    return (
        <BlockOrLoader loading={loading}>
          <div className="box box-primary">
            <div className="box-body">
              <table className="table-list">
                <thead>
                <tr>
                  <th className="cell-name">Товар</th>
                  <th className="cell-price">Цена</th>
                  <th className="cell-discount">Скидка</th>
                  <th className="cell-stock">Наличие</th>
                </tr>
                </thead>
                <tbody>
                {items.map(item => <ListItem key={item.id} id={item.id} />)}
                </tbody>
              </table>
            </div>
          </div>
        </BlockOrLoader>
    );
  }
}

function mapStateToProps(state) {
  return state.list;
}

export default connect(mapStateToProps)(List);