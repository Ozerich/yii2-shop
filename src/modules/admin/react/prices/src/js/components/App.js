import React, { Component } from 'react';
import { connect } from 'react-redux';

import { loadCurrencies } from "../ducks/list";
import Filter from "../sections/Filter";
import List from "../sections/List";
import BlockOrLoader from "../components/ui/BlockOrLoader";

class App extends Component {
  componentWillMount() {
    this.props.loadCurrencies();
  }

  render() {
    return (
        <BlockOrLoader loading={this.props.loading} className="app">
          <Filter />
          <List />
        </BlockOrLoader>
    );
  }
}

function mapStateToProps(state) {
  return {
    loading: state.list.initLoading
  }
}

export default connect(mapStateToProps, { loadCurrencies })(App);
