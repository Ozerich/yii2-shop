import React, { Component } from 'react';
import { connect } from 'react-redux';

import { disableExtendedMode, save } from "../ducks/common";

class CommonSectionExtended extends Component {
  render() {
    const { successNoteVisible } = this.props;
    return (
        <div className="section">
          <a href="#" onClick={this.onDisableExtendedModeClick.bind(this)}>Выключить расширенный режим цен</a>
        </div>
    );
  }

  onSubmit(values) {
    this.props.save(values.price, values.priceDisabled, values.priceDisabledText);
  }

  onDisableExtendedModeClick(e) {
    e.preventDefault();
    this.props.disableExtendedMode();
  }

}

function mapStateToProps(state) {
  return state.common;
}

export default connect(mapStateToProps, { save, disableExtendedMode })(CommonSectionExtended);