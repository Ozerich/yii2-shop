import React, { Component } from 'react';
import { connect } from 'react-redux';

import ListItemProductHeader from "./ListItemProductHeader";

import { changeModule } from "../../ducks/list";
import ListItemModuleRow from "./ListItemModuleRow";

class ListItemModular extends Component {
  render() {
    const { model } = this.props;

    return (
        <>
          <ListItemProductHeader model={model} />
          {model.modules.map(item => <ListItemModuleRow key={item.id} isChild={true} model={item}
                                                        onChange={values => this.onModuleChange(item, values)} />)}
        </>
    );
  }

  onModuleChange(module, data) {
    this.props.changeModule(this.props.model.id, module.id, data);
  }
}

export default connect(null, { changeModule })(ListItemModular);