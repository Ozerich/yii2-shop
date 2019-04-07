import React, { Component } from 'react';
import { connect } from 'react-redux';
import { remove } from "../../ducks/conditions";

class ConditionRowActions extends Component {
  render() {
    return <a href="#" onClick={this.onRemoveClick.bind(this)}>Удалить</a>
  }

  onRemoveClick(e) {
    const { model, remove } = this.props;
    e.preventDefault();

    remove(model.id);
  }
}

export default connect(null, { remove })(ConditionRowActions);