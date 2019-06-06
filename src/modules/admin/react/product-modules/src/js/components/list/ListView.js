import React, { Component } from 'react';
import { connect } from "react-redux";
import ListViewRow from "./ListViewRow";

class ListView extends Component {
  render() {
    const { items, fields } = this.props;

    return (
        <table className="table-list">
          <thead>
          <tr>
            <th className="cell-name">Товар</th>
            <th className="cell-name">Производитель</th>
            {fields.map(item => <th>{item.name}</th>)}
          </tr>
          </thead>
          <tbody>
          {items.map((item, index) => (
                  <>
                    <ListViewRow model={item} key={item.id} />{index > 0 && index % 30 === 0 ? (
                          <tr>
                            <th className="cell-name">Товар</th>
                            <th className="cell-name">Производитель</th>
                            {fields.map(item => <th>{item.name}</th>)}
                          </tr>)
                      : null}
                  </>
              )
          )}
          </tbody>
        </table>
    );
  }
}

function mapStateToProps(state) {
  return {
    items: state.list.items,
    fields: state.list.fields
  }
}

export default connect(mapStateToProps)(ListView);