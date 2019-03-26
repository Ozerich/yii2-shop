import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';
import FormInput from "../Form/FormInput";
import FormSelect from "../Form/FormSelect";
import FormArea from "../Form/FormArea";
import FormActions from "../Form/FormActions";

import { create, hideForm, save } from "../../ducks/field-form";

import { INTEGER, SELECT, values as fieldTypeValues } from "../../constants/FieldType";

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
                  <FormSelect id="group_id" name="group_id" items={this.getGroups()} label="Группа полей"
                              handleChange={handleChange}
                              value={values.group_id} />
                  <FormSelect id="type" name="type" items={fieldTypeValues()} label="Тип поля"
                              handleChange={handleChange}
                              value={values.type} />
                  {this.renderAdditionalFields(handleChange, values)}
                  <FormActions onCancel={this.onCancel.bind(this)} />
                </form>
            )}
          </Formik>
        </div>
    );
  }

  renderAdditionalFields(handleChange, values) {
    switch (values.type) {
      case SELECT:
        return (
            <>
            <FormArea id="values" name="values" label="Значения" handleChange={handleChange} value={values.values} />
            </>
        );
      case INTEGER:
        return (
            <div className="row">
              <div className="col-xs-6">
                <FormInput id="value_prefix" name="value_prefix" label="Префикс значения" handleChange={handleChange}
                           value={values.value_prefix} />
              </div>
              <div className="col-xs-6">
                <FormInput id="value_suffix" name="value_suffix" label="Суффикс значения" handleChange={handleChange}
                           value={values.value_suffix} />
              </div>
            </div>
        );
      default:
        return null;
    }
  }

  getGroups() {
    const { groups } = this.props;

    const result = [{ id: null, label: 'Без группы' }];

    groups.forEach(group => {
      result.push({ id: +group.id, label: group.name });
    });

    return result;
  }

  onCancel() {
    this.props.hideForm();
  }

  onSubmit(values) {
    const { create, save, modelId } = this.props;

    const modelValues = Array.isArray(values.values) ? values.values : (values.values ? values.values.split("\n") : null);

    const model = {
      name: values.name,
      type: values.type,
      group_id: values.group_id ? parseInt(values.group_id) : null,
      value_prefix: values.value_prefix,
      value_suffix: values.value_suffix
    };

    if (modelValues) {
      model.values = modelValues;
    }

    modelId ? save(modelId, model) : create(model);
  }
}

function mapStateToProps(state) {
  const modelId = state.fieldForm.modelId;

  let model = state.fields.entities.find(item => item.id === modelId);

  if (!model) {
    model = { type: 'STRING' };
  }

  return {
    modelId: state.fieldForm.modelId,
    groups: state.groups.entities,
    model: model
  };
}

export default connect(mapStateToProps, { hideForm, create, save })(FieldForm)
