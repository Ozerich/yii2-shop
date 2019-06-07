import React, { Component } from 'react';

class RedButton extends Component {
  render() {
    return <button className="btn btn-mini btn-danger" {...this.props}>{this.props.children}</button>
  }
}

export default RedButton;