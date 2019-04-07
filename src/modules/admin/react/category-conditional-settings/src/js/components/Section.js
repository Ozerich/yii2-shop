import React, { Component } from 'react';
import BlockOrLoader from "./ui/BlockOrLoader";
import BlockWithLoader from "./ui/BlockWithLoader";

class Section extends Component {
  render() {
    const { title, children, loading, innerLoading } = this.props;

    return (
        <div className="list">
          <div className="list-title">
            {title}
            {this.renderButton()}
          </div>
          <BlockWithLoader className="list-body" loading={innerLoading}>
            <BlockOrLoader loading={loading}>
              {children}
            </BlockOrLoader>
          </BlockWithLoader>
        </div>
    );
  }

  renderButton() {
    const { buttonLabel, buttonNote } = this.props;
    if (!buttonLabel) {
      return null;
    }

    return (
        <div className="list-title__right">
          {buttonNote ? <span className="list-title__right-note">{buttonNote}</span> : null}
          <button className="btn btn-success" onClick={this.onButtonClick.bind(this)}>Сохранить</button>
        </div>
    );
  }

  onButtonClick(e) {
    e.preventDefault();
    if (this.props.onButtonClick) {
      this.props.onButtonClick();
    }
  }
}

export default Section;