import React, { Component } from 'react';
import { connect } from 'react-redux';
import FormRadio from "../components/form/FormRadio";

import { MODULE_MODE_CATALOG, MODULE_MODE_SIMPLE } from "../constants/ModuleMode";
import { changeMode } from "../ducks/new";

class NewModuleFormModeSelect extends Component {
  render() {
    const { value } = this.props;

    return (
        <div className="new-module__form-mode">
          <FormRadio name="mode" id="mode_01" label="Самостоятельный товар"
                     selected={value === MODULE_MODE_SIMPLE} onChange={() => this.onChange(MODULE_MODE_SIMPLE)} />
          <FormRadio name="mode" id="mode_02" label="Товар из каталога"
                     selected={value === MODULE_MODE_CATALOG} onChange={() => this.onChange(MODULE_MODE_CATALOG)} />
        </div>
    );
  }

  onChange(value) {
    this.props.changeMode(value);
  }
}

function mapStateToProps(state) {
  return {
    value: state.new.mode
  };
}

export default connect(mapStateToProps, { changeMode })(NewModuleFormModeSelect);