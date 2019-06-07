import React, { Component } from 'react';
import { connect } from 'react-redux';

import { load } from "../ducks/list";
import BlockOrLoader from "../components/loaders/BlockOrLoader";
import ListRow from "./ListRow";

class List extends Component {
  componentWillMount() {
    this.props.load();
  }

  render() {
    const { loading, entities } = this.props;

    return (
        <div className="list">
          <BlockOrLoader loading={loading}>
            <table className="table-list">
              <thead>
              <tr>
                <th className="cell-name">Модуль</th>
                <th className="cell-price">Цена</th>
                <th className="cell-quantity">Количество в<br />стандартном наборе</th>
                <th className="cell-actions">Действия</th>
              </tr>
              </thead>
              <tbody>
              {entities.map(model => <ListRow key={model.id} model={model} />)}
              </tbody>
            </table>
          </BlockOrLoader>
        </div>
    );
  }
}

function mapStateToProps(state) {
  return state.list;
}

export default connect(mapStateToProps, { load })(List);