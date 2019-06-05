import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';

import { init } from "../ducks/form";
import { submit } from "../ducks/list";
import BlockOrLoader from "../components/ui/BlockOrLoader";
import FormView from "../components/form/FormView";

class Form extends Component {
  componentWillMount() {
    this.props.init();
  }

  render() {
    return (
        <div className="box box-primary">
          <div className="box-body">
            <BlockOrLoader loading={this.props.loading}>
              <Formik onSubmit={this.onSubmit.bind(this)}>
                {({ values, handleChange, handleSubmit }) => (
                    <form onSubmit={handleSubmit}>
                      <FormView values={values} handleChange={handleChange} />
                    </form>
                )}
              </Formik>
            </BlockOrLoader>
          </div>
        </div>
    );
  }

  onSubmit(values) {
    this.props.submit(values.category_id, this.props.selected);
  }
}

function mapStateToProps(state) {
  return {
    loading: state.form.loading,
    selected: state.form.selected
  }
}

export default connect(mapStateToProps, { init, submit })(Form);