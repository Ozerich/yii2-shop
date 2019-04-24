import React, { Component } from 'react';
import { connect } from 'react-redux';

import ListItemProductHeader from "./ListItemProductHeader";
import ListItemRow from "./ListItemRow";

import { change } from "../../ducks/list";

class ListItem extends Component {
  render() {
    const { model } = this.props;

    if (model.price.is_extended) {
      return (
          <>
          <ListItemProductHeader model={model} />
          {model.children.map(item => <ListItemRow key={item.id}
                                                   onChange={data => this.onChange(item.params.map(item => item.id), data)}
                                                   name={item.params.map(item => item.value).join(', ')}
                                                   price={item.price}
          />)}
          </>
      );
    } else {
      return <ListItemRow name={model.name} price={model.price} onChange={data => this.onChange(null, data)} />
    }
  }

  onChange(params, value) {
    const { model } = this.props;
    this.props.change(model.id, params, value);
  }
}

function mapStateToProps(state, ownProps) {
  return {
    model: state.list.items.find(item => item.id === +ownProps.id)
  };
}

export default connect(mapStateToProps, { change })(ListItem);