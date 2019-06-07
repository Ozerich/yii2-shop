import React, { Component } from 'react';
import { connect } from 'react-redux';

import { disableExtendedMode, save, saveCurrency } from "../ducks/common";
import FormSelect from "../components/Form/FormSelect";

class CommonSectionExtended extends Component {
  render() {
    return (
        <div className="app-header">
          <a href="#" onClick={this.onDisableExtendedModeClick.bind(this)}>Выключить расширенный режим цен</a>
          <div className="currency-switcher">
            {this.renderCurrencySwitcher()}
          </div>
        </div>
    );
  }

  renderCurrencySwitcher() {
    const { currencyEnabled, currencies, model } = this.props;

    if (!currencyEnabled) {
      return null;
    }

    return <FormSelect items={currencies} value={model.currency_id} onChange={this.onCurrencyChange.bind(this)}
                       id="currencies" />
  }

  onCurrencyChange(value) {
    const { saveCurrency } = this.props;

    saveCurrency(value);
  }

  onSubmit(values) {
    this.props.save(values.price, values.priceDisabled, values.priceDisabledText);
  }

  onDisableExtendedModeClick(e) {
    e.preventDefault();
    this.props.disableExtendedMode();
  }

}

function mapStateToProps(state) {
  return state.common;
}

export default connect(mapStateToProps, { save, disableExtendedMode, saveCurrency })(CommonSectionExtended);