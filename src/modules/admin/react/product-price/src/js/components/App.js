import React, { Component } from 'react';
import { connect } from 'react-redux';

class App extends Component {
  render() {
    return (
        <>

        </>
    );
  }
}

function mapStateToProps(state, ownProps) {
  return {}
}

export default connect(mapStateToProps, {})(App);
