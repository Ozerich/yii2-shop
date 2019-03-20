import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';
import FormInput from "../Form/FormInput";
import FormActions from "../Form/FormActions";

import { create, hideForm, save } from "../../ducks/group-form";

class GroupForm extends Component {
  render() {
    return (
        <div className="form">
          <Formik initialValues={{ name: this.props.name }} onSubmit={this.onSubmit.bind(this)}>
            {({ values, handleChange, handleSubmit }) => (
                <form onSubmit={handleSubmit}>
                  <FormInput id="name" label="Название группы" handleChange={handleChange} value={values.name} />
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

    modelId ? save(modelId, values.name) : create(values.name);
  }
}

function mapStateToProps(state) {
  const modelId = state.groupForm.modelId;

  const model = state.groups.entities.find(item => item.id === modelId);

  return {
    modelId: state.groupForm.modelId,
    name: model ? model.name : null
  };
}

export default connect(mapStateToProps, { hideForm, create, save })(GroupForm)
