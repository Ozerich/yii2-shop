import React, { Component } from 'react';
import { connect } from "react-redux";
import Button from "../components/buttons/Button";

import { open } from "../ducks/new";
import NewModuleForm from "./NewModuleForm";

class NewModule extends Component {
  render() {
    const { opened, open } = this.props;

    return (
        <div className="new-module">
          {opened ? <NewModuleForm /> : (
              <div className="new-module__create">
                <Button onClick={() => open()}>Добавить модуль</Button>
              </div>
          )}
        </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    opened: state.new.opened
  }
}

export default connect(mapStateToProps, { open })(NewModule);
