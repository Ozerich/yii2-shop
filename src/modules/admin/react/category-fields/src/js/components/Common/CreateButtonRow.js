import React, { Component } from 'react';

class CreateButtonRow extends Component {
  render() {
    const { label } = this.props;
    return (
        <div className="create-button-row">
          <button className="btn btn-mini btn-success" onClick={this.onClick.bind(this)}>{label}</button>
        </div>
    );
  }

  onClick(){
    if(this.props.onClick){
      this.props.onClick();
    }
  }
}

export default CreateButtonRow;