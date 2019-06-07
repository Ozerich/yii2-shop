import React, { Component } from 'react';
import classNames from 'classnames';

class FormImagesItem extends Component {
  render() {
    const { model, loading } = this.props;

    console.log(model);
    return (
        <div className={classNames("form-images__file", { "form-images__file--loading": loading })}>
          {model.imageUrl ? <img src={model.imageUrl} /> : null}
        </div>
    );
  }
}

export default FormImagesItem;