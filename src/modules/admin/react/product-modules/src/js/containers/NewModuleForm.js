import React, { Component } from 'react';
import { connect } from "react-redux";

import Button from "../components/buttons/Button";
import RedButton from "../components/buttons/RedButton";

import NewModuleFormModeSelect from "./NewModuleFormModeSelect";
import NewModuleFormSimple from "./NewModuleFormSimple";

import { MODULE_MODE_CATALOG, MODULE_MODE_SIMPLE } from "../constants/ModuleMode";
import { close, createFromCatalog } from "../ducks/new";
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
              {this.isSubmitVisible() ? <Button onClick={this.handleSubmitMyForm}>Добавить</Button> : null}
            </div>
          </div>
        </div>
    );
  }

  isSubmitVisible() {
    const { value } = this.props;

    if (value === MODULE_MODE_SIMPLE) {
      return true;
    } else if (value === MODULE_MODE_CATALOG) {
      return this.props.selected !== null;
    }
  }

  submitCatalogMode() {
    const { selected } = this.props;

    this.props.createFromCatalog(selected.id);
  }

  submitMyForm = null;

  handleSubmitMyForm = (e) => {
    const { value } = this.props;

    if (value === MODULE_MODE_SIMPLE) {
      if (this.submitMyForm) {
        this.submitMyForm(e);
      }
    } else {
      this.submitCatalogMode();
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
    value: state.new.mode,
    selected: state.new.catalogSelectedProduct,
  };
}

export default connect(mapStateToProps, { close, createFromCatalog })(NewModuleForm);
