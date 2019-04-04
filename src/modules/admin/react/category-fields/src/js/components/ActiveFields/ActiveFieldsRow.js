import React, { Component } from 'react';
import { connect } from 'react-redux';

import { setAsActive, setAsInActive } from "../../ducks/active";

class ActiveFieldsRow extends Component {
  render() {
    const { model, checked } = this.props;

    return (
        <div className="list-row">
          <div className="field-list__checkbox">
            <input type="checkbox" id={"checkbox_" + model.id} checked={checked} onChange={this.onChange.bind(this)} />
          </div>
          <div className="field-list__name">
            <label htmlFor={"checkbox_" + model.id}>{model.name + (model.group_name ? '(' + model.group_name + ')' : '')}</label>
          </div>
        </div>
    );
  }

  onChange(e) {
    const { model, setAsActive, setAsInActive } = this.props;


    if (e.target.checked) {
      setAsActive(model.id);
    } else {
      setAsInActive(model.id);
    }
  }
}

function mapStateToProps(state, ownProps) {
  return {
    checked: state.active.activeIds.indexOf(ownProps.model.id) !== -1
  };
}

export default connect(mapStateToProps, { setAsActive, setAsInActive })(ActiveFieldsRow);