import React, { Component } from 'react';

import "./styles.scss";
import Dropzone from "./Dropzone";
import FormImagesItem from "./FormImagesItem";

class FormImages extends Component {
  constructor(props) {
    super(props);

    this.state = {
      files: [],

      loadingIds: []
    };
  }

  render() {
    const { id, label, value, handleChange } = this.props;

    return (
        <div className="form-group required">
          <label className="control-label" htmlFor={id}>{label}</label>
          <div className="form-images">
            {this.state.files.map(model => <FormImagesItem model={model}
                                                           loading={this.state.loadingIds.indexOf(model.id) !== -1} />)}

            <div className="form-images__new">
              <Dropzone onFilesAdded={this.onFilesAdded.bind(this)} />
            </div>
          </div>
        </div>
    );
  }

  showLoading(fileId) {
    this.setState({
      loadingIds: [...this.state.loadingIds, fileId]
    });
  }

  hideLoading(fileId) {
    this.setState({
      loadingIds: this.state.loadingIds.filter(id => id !== fileId)
    });
  }

  updateImage(fileId, image) {

    const stateImages = this.state.files.map(item => {
      if (item.id !== fileId) {
        return item;
      }
      return Object.assign({}, item, {
        uploaded: true,
        imageUrl: image.url,
        serverId: image.id
      });
    });

    this.setState({
      files: stateImages.slice(0)
    });

    this.forceUpdate();

    this.updateValue();
  }

  updateValue() {
    const { id, setFieldValue } = this.props;

    const value = this.state.files.map(item => item.serverId);

    setFieldValue(id, value);
  }

  upload(fileId) {
    if (!this.props.onUpload) {
      return;
    }

    const file = this.state.files.find(item => item.id === fileId);
    if (!file) {
      return;
    }

    this.showLoading(fileId);

    this.props.onUpload(file.browserModel).then(model => {
      this.hideLoading(fileId);
      this.updateImage(fileId, model.image);
    }).catch(error => {
      this.hideLoading(fileId);
    });
  }

  onFilesAdded(files) {
    const stateFiles = this.state.files;

    files.forEach(file => {
      const fileId = Math.random();

      stateFiles.push({
        id: fileId,
        serverId: null,
        imageUrl: null,
        loading: false,
        browserModel: file
      });

      this.upload(fileId);
    });

    this.setState({ files: stateFiles });
  }
}

export default FormImages;