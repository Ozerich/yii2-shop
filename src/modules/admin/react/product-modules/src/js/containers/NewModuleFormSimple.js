import React, { Component } from 'react';
import { connect } from "react-redux";
import { Formik } from "formik";
import NewModuleFormSimpleFormView from "./NewModuleFormSimpleFormView";

import { create } from '../ducks/new';

class NewModuleFormSimple extends Component {
  render() {
    const { bindSubmitForm } = this.props;

    return (
        <Formik initialValues={{ name: '' }} onSubmit={this.onSubmit.bind(this)}>
          {({ values, handleChange, handleSubmit, submitForm, setFieldValue }) => {
            bindSubmitForm(submitForm);

            return (
                <form noValidate onSubmit={handleSubmit}>
                  <NewModuleFormSimpleFormView values={values} setFieldValue={setFieldValue} handleChange={handleChange} />
                </form>
            )
          }}
        </Formik>
    );
  }

  onSubmit(values, { setSubmitting }) {
    setSubmitting(false);

    this.props.create(values);
  }
}

function mapStateToProps(state) {
  return {
    loading: state.new.loading
  };
}

export default connect(mapStateToProps, { create })(NewModuleFormSimple);
