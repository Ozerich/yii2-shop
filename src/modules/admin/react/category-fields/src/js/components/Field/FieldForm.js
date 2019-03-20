import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';
import FormInput from "../Form/FormInput";
import FormSelect from "../Form/FormSelect";
import FormActions from "../Form/FormActions";

import { create, hideForm, save } from "../../ducks/field-form";

import { values as fieldTypeValues } from "../../constants/FieldType";

class FieldForm extends Component {
  render() {
    const { model } = this.props;

    return (
        <div className="form">
          <Formik initialValues={model} onSubmit={this.onSubmit.bind(this)}>
            {({ values, handleChange, handleSubmit }) => (
                <form onSubmit={handleSubmit}>
                  <FormInput id="name" name="name" label="Название поля" handleChange={handleChange}
                             value={values.name} />
                  <FormSelect id="type" name="type" items={fieldTypeValues()} label="Тип поля"
                              handleChange={handleChange}
                              value={values.type} />
                  <FormActions onCancel={this.onCancel.bind(this)} />
                </form>
            )}
          </Formik>
        </div>
    );
  }

  onCancel() {
    this.props.hideForm();
  }

  onSubmit(values) {
    const { create, save, modelId } = this.props;

    const model = {
      name: values.name,
      type: values.type
    };

    modelId ? save(modelId, model) : create(model);
  }
}

function mapStateToProps(state) {
  const modelId = state.fieldForm.modelId;

  const model = state.fields.entities.find(item => item.id === modelId);

  return {
    modelId: state.fieldForm.modelId,
    model: model
  };
}

export default connect(mapStateToProps, { hideForm, create, save })(FieldForm)
