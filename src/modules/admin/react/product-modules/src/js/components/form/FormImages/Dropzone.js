import React, { Component } from 'react';

class Dropzone extends Component {
  constructor(props) {
    super(props);
    this.fileInputRef = React.createRef();
  }

  render() {
    return (
        <div className="dropzone" onClick={this.openFileDialog.bind(this)}>
          <svg className="dropzone__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43"
               viewBox="0 0 50 43">
            <path
                d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path>
          </svg>

          <input
              ref={this.fileInputRef}
              className="FileInput"
              type="file"
              multiple
              onChange={this.onFilesAdded.bind(this)}
          />
        </div>
    );
  }

  openFileDialog() {
    this.fileInputRef.current.click();
  }

  fileListToArray(list) {
    const array = [];
    for (let i = 0; i < list.length; i++) {
      array.push(list.item(i));
    }
    return array;
  }

  onFilesAdded(evt) {
    const files = evt.target.files;
    const filesArray = this.fileListToArray(files);

    if (this.props.onFilesAdded) {
      this.props.onFilesAdded(filesArray);
    }
  }

}

export default Dropzone;