import React, { Component } from 'react';
import FormInput from "../Form/FormInput";

class ParamForm extends Component {
  constructor(props) {
    super(props);

    this.state = {
      name: props.model ? props.model.name : ''
    };
  }

  onNameChange = name => this.setState({ name });

  render() {
    return (
        <div className="param-form">
          <FormInput id="name" label="Название параметра" value={this.state.name} onChange={this.onNameChange} />
          <div className="param-form__actions">
            <button className="btn btn-danger" onClick={this.onCancel.bind(this)}>Отмена</button>
            <button className="btn btn-success" onClick={this.onSubmit.bind(this)}>Создать</button>
          </div>
        </div>
    );
  }

  onCancel() {
    if (this.props.onCancel) {
      this.props.onCancel();
    }
  }

  onSubmit() {
    if (this.state.name.length === 0) {
      return;
    }

    if (this.props.onSave) {
      this.props.onSave(this.state.name);
    }

    this.onCancel();
  }
}

export default ParamForm;