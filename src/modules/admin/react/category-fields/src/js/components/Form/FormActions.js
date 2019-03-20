import React, { Component } from 'react';

class FormActions extends Component {
  render() {
    const { isCreate } = this.props;

    return (
        <div className="form-actions">
          <button className="btn btn-danger" onClick={this.onCancel.bind(this)}>Отмена</button>
          <button className="btn btn-success" type="submit">{isCreate ? 'Создать' : 'Сохранить'}</button>
        </div>
    );
  }

  onCancel() {
    if (this.props.onCancel) {
      this.props.onCancel();
    }
  }
}

export default FormActions;