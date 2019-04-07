import React, { Component } from 'react';

class FormInput extends Component {
  render() {
    const { id, value } = this.props;

    return (
        <div className="form-group">
          <input {...this.props} id={id} className="form-control" value={value}
                 onChange={this.onChange.bind(this)} />
        </div>
    );
  }

  onChange(e) {
    if (this.props.onChange) {
      this.props.onChange(e.target.value);
    }
  }
}

export default FormInput;