import React, { Component } from 'react';
import { connect } from 'react-redux';

import ListItemProductHeader from "./ListItemProductHeader";

import { change } from "../../ducks/list";
import ListItemModuleRow from "./ListItemModuleRow";

class ListItemModular extends Component {
  render() {
    const { model } = this.props;

    return (
        <>
          <ListItemProductHeader model={model} />
          {model.modules.map(item => <ListItemModuleRow key={item.id} isChild={true} model={item} />)}
        </>
    );
  }
}

export default connect(null, { change })(ListItemModular);