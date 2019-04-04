import React, { Component } from 'react';
import { connect } from 'react-redux';

import { disable, enable } from "../../ducks/fields";

class FieldRow extends Component {
  render() {
    const { model, checked } = this.props;

    return (
        <div className="list-row">
          <div className="field-list__checkbox">
            <input type="checkbox" id={"checkbox_" + model.id} checked={checked} onChange={this.onChange.bind(this)} />
          </div>
          <div className="field-list__name">
            <label
                htmlFor={"checkbox_" + model.id}>{model.name + (model.group_name ? '(' + model.group_name + ')' : '')}</label>
          </div>
        </div>
    );
  }

  onChange(e) {
    const { model, enable, disable } = this.props;

    if (e.target.checked) {
      enable(model.id);
    } else {
      disable(model.id);
    }
  }
}

function mapStateToProps(state, ownProps) {
  return {
    checked: state.fields.activeIds.indexOf(ownProps.model.id) !== -1
  };
}

export default connect(mapStateToProps, { enable, disable })(FieldRow);