import React, { Component } from 'react';
import { connect } from 'react-redux';

import { closeCreateForm, openCreateForm } from "../ducks/params-form";
import { createParam } from "../ducks/params";

import SectionHeader from "../components/Section/SectionHeader";
import ParamForm from "../components/Param/ParamForm";
import ParamContainer from "../components/Param/ParamContainer";

class ParamsSection extends Component {
  render() {
    const { formOpened, params } = this.props;

    return (
        <div className="section">
          <SectionHeader title="Параметры"
                         buttonLabel={params.length === 2 ? null : (formOpened ? 'Закрыть форму' : "Добавить параметр")}
                         buttonClass={formOpened ? 'danger' : 'success'}
                         onButtonClick={() => this.onHeaderButtonClick()}
          />

          {formOpened &&
          <ParamForm model={null} onCancel={this.onFormCancel.bind(this)} onSave={this.onFormSave.bind(this)} />}

          <div className="section-content">
            {params.map((model, ind) => <ParamContainer isFirst={ind === 0} isLast={ind === params.length - 1}
                                                        id={model.id} />)}
          </div>
        </div>
    );
  }

  onFormCancel() {
    this.props.closeCreateForm();
  }

  onFormSave(name) {
    const { createParam, productId } = this.props;

    createParam(productId, name);
  }

  onHeaderButtonClick() {
    const { formOpened, openCreateForm, closeCreateForm } = this.props;

    formOpened ? closeCreateForm() : openCreateForm();
  }
}

function mapStateToProps(state) {
  return {
    productId: state.common.productId,
    formOpened: state.paramsForm.formOpened,
    params: state.params.params
  };
}

export default connect(mapStateToProps, { openCreateForm, closeCreateForm, createParam })(ParamsSection);