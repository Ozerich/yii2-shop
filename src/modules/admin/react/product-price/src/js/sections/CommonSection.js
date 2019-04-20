import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';

import FormInput from '../components/Form/FormInput';
import FormCheckbox from "../components/Form/FormCheckbox";
import FormSelect from "../components/Form/FormSelect";

import { enableExtendedMode, save } from "../ducks/common";
import CommonSectionDiscountParams from "./CommonSectionDiscountParams";

const discountModes = [
  { id: 'FIXED', label: 'Цена со скидкой' },
  { id: 'AMOUNT', label: 'Скидка на сумму' },
  { id: 'PERCENT', label: 'Скидка в процентах' },
];

class CommonSection extends Component {
  getInitialValues() {
    const { model } = this.props;

    return {
      price: model.price,
      priceDisabled: model.price_hidden,
      priceDisabledText: model.price_hidden_text,

      discountEnabled: !!model.discount_mode,
      discountMode: model.discount_mode || 'FIXED',
      discountValue: model.discount_value
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
                  </div> : (
                      <>
                      <div className="row">
                        <div className="col-xs-12">
                          <FormCheckbox noMargin label="Включить скидку" id="discountEnabled"
                                        value={values.discountEnabled}
                                        handleChange={handleChange} />
                        </div>
                      </div>

                      {values.discountEnabled ? (
                          <>
                          <div className="row">

                            <div className="col-xs-12">
                              <FormSelect id="discountMode" label="Тип скидки"
                                          handleChange={handleChange}
                                          value={values.discountMode} items={discountModes}
                              />
                            </div>
                          </div>
                          <CommonSectionDiscountParams mode={values.discountMode}
                                                       values={values}
                                                       handleChange={handleChange} />
                          </>

                      ) : null}
                      </>
                  )}

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
    this.props.save(
        values.price,
        values.priceDisabled,
        values.priceDisabledText,
        values.discountEnabled ? values.discountMode : null,
        values.discountEnabled ? values.discountValue : null
    );
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