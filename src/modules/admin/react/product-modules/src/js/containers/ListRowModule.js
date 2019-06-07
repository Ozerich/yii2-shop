import React, { Component } from 'react';

class ListRowModule extends Component {
  render() {
    const { model } = this.props;
    return (
        <div className="list-row__item">
          <span className="list-row__item-name">{model.name}</span>

          {model.params && model.params.length > 0 ? (
              <ul className="list-row__item-params">
                {model.params.map(param => <li>{param.param}: {param.value} см.</li>)}
              </ul>
          ) : null}
        </div>
    );
  }
}

export default ListRowModule;