import React, { Component } from 'react';

class ListRowModule extends Component {
  render() {
    const { model } = this.props;
    return (
        <div className="list-row__item">
          {
            model.image ? <div className="list-row__item-image">
              <img src={model.image} />
            </div> : null
          }
          <span className="list-row__item-name">{model.name}</span>
        </div>
    );
  }
}

export default ListRowModule;