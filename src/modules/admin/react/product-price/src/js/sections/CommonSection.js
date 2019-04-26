import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Formik } from 'formik';

import FormInput from '../components/Form/FormInput';
import FormCheckbox from "../components/Form/FormCheckbox";
import FormSelect from "../components/Form/FormSelect";
import FormArea from "../components/Form/FormArea";

import { enableExtendedMode, save } from "../ducks/common";
import CommonSectionDiscountParams from "./CommonSectionDiscountParams";

import { FIXED, items as discountModes } from "../constants/DiscountMode";
import { items as stockModes, NO, WAITING } from "../constants/Stock";


class CommonSection extends Component {
  getInitialValues() {
    const { model } = this.props;

    return {
      price: model.price,
      priceDisabled: model.price_hidden,
      priceDisabledText: model.price_hidden_text,

      discountEnabled: !!model.discount_mode,
      discountMode: model.discount_mode || FIXED,
      discountValue: model.discount_value,

      stock: model.stock || NO,
      stock_waiting_days: model.stock_waiting_days,

      priceNote: model.price_note,
      isPriceFrom: !!model.is_price_from,

      currency: model.currency_id ? model.currency_id : null
    }
  }

  renderPriceInput(values, handleChange) {
    return (
        <FormInput id="price" label="Цена"
                   handleChange={handleChange}
                   disabled={values.priceDisabled}
                   value={values.price} />
    );
  }

  renderPrice(values, handleChange) {
    const { currencyEnabled, currencies } = this.props;

    if (currencyEnabled === false) {
      return (
          <div className="col-xs-6">
            {this.renderPriceInput(values, handleChange)}
          </div>
      );
    }
    return (
        <div className="col-xs-6">
          <div className="price-input-wrapper">
            <div>
              {this.renderPriceInput(values, handleChange)}
            </div>
            <div>
              <FormSelect handleChange={handleChange} id="currency" value={values.currency}
                          items={currencies} />
            </div>
          </div>
        </div>
    );
  }

  render() {
    const { successNoteVisible } = this.props;
    return (
        <div className="section">
          <Formik initialValues={this.getInitialValues()} onSubmit={this.onSubmit.bind(this)}>
            {({ values, handleChange, handleSubmit }) => (
                <form onSubmit={handleSubmit}>
                  <div className="row">
                    {this.renderPrice(values, handleChange)}

                    <div className="col-xs-3">
                      <FormCheckbox id="priceFrom" label={"Минимальная цена"}
                                    handleChange={handleChange}
                                    value={values.isPriceFrom} />
                    </div>
                    <div className="col-xs-3">
                      <FormCheckbox id="priceDisabled" label="Цена неизвестна"
                                    handleChange={handleChange}
                                    value={values.priceDisabled} />
                    </div>
                  </div>

                  <div className="row">
                    <div className="col-xs-12">
                      <FormArea id="priceNote" label="Комментарий к цене" handleChange={handleChange}
                                value={values.priceNote} />
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
                                          value={values.discountMode} items={discountModes()}
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

                  <div className="row">
                    <div className="col-xs-6">
                      <FormSelect items={stockModes()} label="Наличие" id="stock" value={values.stock}
                                  handleChange={handleChange} />
                    </div>
                    {values.stock === WAITING ? <div className="col-xs-6">
                      <FormInput label="Макс. кол-во дней" id="stock_waiting_days" value={values.stock_waiting_days}
                                 handleChange={handleChange} />
                    </div> : null}
                  </div>

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
        values.discountEnabled ? values.discountValue : null,
        values.stock,
        values.stock === WAITING ? values.stock_waiting_days : null,
        values.priceNote,
        values.isPriceFrom,
        values.currency
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