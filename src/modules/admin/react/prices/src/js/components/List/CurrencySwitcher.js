import React, { Component } from 'react';
import { connect } from 'react-redux';

import { changeProductCurrency } from "../../ducks/list";
import FormSelect from "../Form/Select";

class CurrencySwitcher extends Component {
  render() {
    const { model, currencies } = this.props;

    if (!currencies || currencies.length < 2) {
      return null;
    }

    return (
        <div className="currency-switcher">
          <FormSelect options={currencies} defaultValue={model.currency_id}
                      onChange={this.onChangeCurrency.bind(this)} />
        </div>
    );
  }

  onChangeCurrency(value) {
    this.props.changeProductCurrency(this.props.model.id, value);
  }
}

function mapStateToProps(state) {
  return {
    currencies: state.list.currencies
  }
}

export default connect(mapStateToProps, { changeProductCurrency })(CurrencySwitcher);