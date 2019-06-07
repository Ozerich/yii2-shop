import React, { Component } from 'react';

class SectionHeader extends Component {
  render() {
    const { title, buttonLabel, buttonClass } = this.props;

    return (
        <div className="section-header">
          <span className="section-title">{title}</span>
          {buttonLabel ? <button className={"section-header__button btn btn-" + buttonClass}
                                 onClick={() => this.onBtnClick()}>{buttonLabel}</button> : null}
        </div>
    );
  }

  onBtnClick() {
    if (this.props.onButtonClick) {
      this.props.onButtonClick();
    }
  }
}

export default SectionHeader;