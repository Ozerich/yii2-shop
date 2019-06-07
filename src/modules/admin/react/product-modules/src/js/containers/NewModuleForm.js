import React, { Component } from 'react';
import { connect } from "react-redux";

import Button from "../components/buttons/Button";
import RedButton from "../components/buttons/RedButton";

import NewModuleFormModeSelect from "./NewModuleFormModeSelect";
import NewModuleFormSimple from "./NewModuleFormSimple";

import { MODULE_MODE_CATALOG, MODULE_MODE_SIMPLE } from "../constants/ModuleMode";
import { close } from "../ducks/new";
import NewModuleFormCatalog from "./NewModuleFormCatalog";

class NewModuleForm extends Component {
  render() {
    const { close } = this.props;

    return (
        <div className="box box-primary new-module__form">
          <div className="box-header">
            <span className="box-title">Новый модуль</span>
          </div>
          <div className="box-body">
            <NewModuleFormModeSelect />
            {this.renderModeInner()}
          </div>
          <div className="box-footer">
            <div className="new-module__form-footer">
              <RedButton onClick={() => close()}>Отмена</RedButton>
              <Button onClick={this.handleSubmitMyForm}>Добавить</Button>
            </div>
          </div>
        </div>
    );
  }

  submitMyForm = null;

  handleSubmitMyForm = (e) => {
    if (this.submitMyForm) {
      this.submitMyForm(e);
    }
  };

  bindSubmitForm = (submitForm) => {
    this.submitMyForm = submitForm;
  };

  renderModeInner() {
    const { value } = this.props;

    switch (value) {
      case MODULE_MODE_SIMPLE:
        return <NewModuleFormSimple bindSubmitForm={this.bindSubmitForm} />;
      case MODULE_MODE_CATALOG:
        return <NewModuleFormCatalog />;
      default:
        return null;
    }
  }
}

function mapStateToProps(state) {
  return {
    value: state.new.mode
  };
}

export default connect(mapStateToProps, { close })(NewModuleForm);
