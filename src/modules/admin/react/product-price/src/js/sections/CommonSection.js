import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';

import FormInput from '../components/Form/FormInput';
import FormCheckbox from "../components/Form/FormCheckbox";

import { enableExtendedMode, save } from "../ducks/common";

class CommonSection extends Component {
  getInitialValues() {
    const { model } = this.props;

    return {
      price: model.price,
      priceDisabled: model.price_hidden,
      priceDisabledText: model.price_hidden_text
    }
  }

  render() {
    const { successNoteVisible } = this.props;
    return (
        <div className="section">
          <Formik initialValues={this.getInitialValues()} onSubmit={this.onSubmit.bind(this)}>
            {({ values, handleChange, handleSubmit }) => (
                <form onSubmit={handleSubmit}>
                  <div className="row">
                    <div className="col-xs-6">
                      <FormInput id="price" label="Цена"
                                 handleChange={handleChange}
                                 disabled={values.priceDisabled}
                                 value={values.price} />
                    </div>
                    <div className="col-xs-2">
                      <FormCheckbox id="priceDisabled" label="Цена неизвестна"
                                    handleChange={handleChange}
                                    value={values.priceDisabled} />
                    </div>
                  </div>
                  {values.priceDisabled ? <div className="row">
                    <div className="col-xs-12">
                      <FormInput id="priceDisabledText" label="Причина"
                                 handleChange={handleChange}
                                 value={values.priceDisabledText} />
                    </div>
                  </div> : null}
                  <button className="btn btn-success">Сохранить</button>
                  &nbsp;или&nbsp;
                  <a href="#" onClick={this.onEnableExtended.bind(this)}>Включить расширенный режим цен</a>
                  {successNoteVisible ? <p>Сохранено</p> : null}
                </form>
            )}
          </Formik>
        </div>
    );
  }

  onSubmit(values) {
    this.props.save(values.price, values.priceDisabled, values.priceDisabledText);
  }

  onEnableExtended(e) {
    e.preventDefault();
    this.props.enableExtendedMode();
  }

}

function mapStateToProps(state) {
  return state.common;
}

export default connect(mapStateToProps, { save, enableExtendedMode })(CommonSection);