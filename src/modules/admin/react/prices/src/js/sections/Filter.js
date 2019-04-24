import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';
import { load } from '../ducks/list';
import { init } from '../ducks/filter';

import FormSelect from '../components/Formik/FormSelect';
import BlockOrLoader from '../components/ui/BlockOrLoader';
import FormCheckbox from "../components/Formik/FormCheckbox";

class Filter extends Component {
  componentWillMount() {
    this.props.init();
  }

  render() {

    return (
        <div className="box box-primary">
          <div className="box-body">
            <BlockOrLoader loading={this.props.loading}>
              <Formik initialValues={{ category_id: null, manufacture_id: null, withoutPrice: false }}
                      onSubmit={this.onSubmit.bind(this)}>
                {({ values, handleChange, handleSubmit }) => (
                    <form onSubmit={handleSubmit}>
                      <div className="row">
                        <div className="col-xs-6">
                          <FormSelect id="category_id" name="category_id" label="Категория" handleChange={handleChange}
                                      emptyValue="Все категории"
                                      items={this.props.categories} />
                        </div>
                        <div className="col-xs-6">
                          <FormSelect id="manufacture_id"
                                      emptyValue="Все производители"
                                      name="manufacture_id" label="Производитель"
                                      handleChange={handleChange}
                                      items={this.props.manufactures} />
                        </div>
                      </div>
                      <div className="row">
                        <div className="col-xs-3">
                          <FormCheckbox label="Без цены" id="without_price" handleChange={handleChange}
                                        checked={values.withoutPrice} />
                        </div>
                      </div>
                      <div className="row">
                        <div className="col-xs-12">
                          <button className="btn btn-success">Показать</button>
                        </div>
                      </div>
                    </form>
                )}
              </Formik>
            </BlockOrLoader>
          </div>
        </div>
    );
  }

  onSubmit(values) {
    this.props.load(values);
  }
}

function mapStateToProps(state) {
  return state.filter;
}

export default connect(mapStateToProps, { init, load })(Filter);