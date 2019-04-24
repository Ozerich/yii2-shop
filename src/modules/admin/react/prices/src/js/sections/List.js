import React, { Component } from 'react';
import { connect } from 'react-redux';
import ListItem from "../components/List/ListItem";

class List extends Component {
  render() {
    const { loading, items } = this.props;

    if (loading) {
      return 'Loading';
    }

    return (
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
    );
  }
}

function mapStateToProps(state) {
  return state.list;
}

export default connect(mapStateToProps)(List);