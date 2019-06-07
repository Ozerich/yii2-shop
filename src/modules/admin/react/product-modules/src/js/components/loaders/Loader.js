import React, { Component } from 'react';

class Loader extends Component {
  render() {
    return (
        <div className="overlay">
          <i className="fa fa-refresh fa-spin" />
        </div>
    );
  }
}

export default Loader;