import React, { Component } from 'react';
import FormInput from "../components/form/FormInput";
import NewModuleFormSimpleFormPrices from "./NewModuleFormSimpleFormPrices";

class NewModuleFormSimpleFormView extends Component {
  render() {
    const { handleChange, values } = this.props;

    return (
        <>
          <div className="row">
            <div className="col-xs-8">
              <FormInput label="Название" id="name" value={values.name} handleChange={handleChange} />
            </div>
            <div className="col-xs-4">
              <FormInput label="Артикул" id="sku" value={values.sku} handleChange={handleChange} />
            </div>
          </div>
          <div className="row">
            <div className="col-xs-12">
              <FormInput label="Комментарий" id="note" value={values.note} handleChange={handleChange} />
            </div>
          </div>
          <NewModuleFormSimpleFormPrices {...this.props} />
        </>
    );
  }
}

export default NewModuleFormSimpleFormView;
