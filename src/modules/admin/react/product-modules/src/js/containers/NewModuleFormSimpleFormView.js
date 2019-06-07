import React, { Component } from 'react';
import FormInput from "../components/form/FormInput";
import NewModuleFormSimpleFormPrices from "./NewModuleFormSimpleFormPrices";
import FormImages from "../components/form/FormImages/FormImages";

import CommonService from '../services/common';

const service = new CommonService;

class NewModuleFormSimpleFormView extends Component {
  render() {
    const { handleChange, values, setFieldValue } = this.props;

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
              <FormImages label="Картинки" id="images" value={values.images}
                          setFieldValue={setFieldValue}
                          onUpload={file => service.upload(file)}
              />
            </div>
          </div>

          <div className="row">
            <div className="col-xs-4">
              <FormInput label="Ширина, см." id="width" value={values.width} handleChange={handleChange} />
            </div>
            <div className="col-xs-4">
              <FormInput label="Высота, см." id="height" value={values.height} handleChange={handleChange} />
            </div>
            <div className="col-xs-4">
              <FormInput label="Глубина, см." id="depth" value={values.depth} handleChange={handleChange} />
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
