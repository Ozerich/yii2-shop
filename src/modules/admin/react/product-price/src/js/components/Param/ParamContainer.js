import React, { Component } from 'react'
import { connect } from 'react-redux';

import {
  closeUpdateForm,
  createParamItem,
  deleteParam,
  deleteParamItem,
  moveParam,
  moveParamItem,
  openUpdateForm,
  saveParam,
  updateParamItem,
  loadPrices
} from "../../ducks/params";

import ParamForm from '../../components/Param/ParamForm';
import ParamItem from "./ParamItem";

class ParamContainer extends Component {
  render() {
    const { model, items, isFirst, isLast } = this.props;

    return (
        <div className="param">
          <div className="param-title">
            <span className="param-title__value">{model.name}</span>
            <div className="param-title__actions">
              {isFirst ? null :
                  <a href="#" className="param-edit" onClick={e => this.onMoveClick(e, -1)}>Поднять выше</a>}
              {isLast ? null :
                  <a href="#" className="param-edit" onClick={e => this.onMoveClick(e, 1)}>Опустить ниже</a>}
              <a href="#" className="param-edit" onClick={e => this.onUpdateClick(e)}>Изменить</a>
              <a href="#" className="param-delete" onClick={e => this.onDeleteClick(e)}>Удалить</a>
            </div>
          </div>

          {model.formOpened ?
              <ParamForm model={model}
                         onCancel={this.onFormCancel.bind(this)}
                         onSave={this.onFormSave.bind(this)} /> : null}
          {model.itemsLoading ? null : <table>
            <thead>
            <tr>
              <th>Название</th>
              <th>Описание</th>
              <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            {items.map((model, ind) => <ParamItem model={model.model}
                                                  key={model.id}
                                                  isFirst={ind === 0}
                                                  isLast={ind === items.length - 1}
                                                  canDelete={model.serverId !== null}
                                                  onUpdate={(itemModel) => this.onUpdateParamItem(model.id, itemModel)}
                                                  onMove={direction => this.onMoveParamItem(model.id, direction)}
                                                  onDelete={() => this.onDeleteParamItem(model.id)} />)}
            </tbody>
          </table>}
          <div className="param-footer">
            <button className="btn btn-primary" onClick={this.onParamItemCreate.bind(this)}>Добавить значение</button>
          </div>
        </div>
    );
  }

  onMoveClick(e, direction) {
    e.preventDefault();
    const { moveParam, model } = this.props;

    moveParam(model.id, direction);
  }

  onUpdateParamItem(id, itemModel) {
    const { model, updateParamItem } = this.props;

    updateParamItem(model.id, id, itemModel);
  }

  onMoveParamItem(id, direction) {
    const { model, moveParamItem } = this.props;
    moveParamItem(model.id, id, direction);
  }

  onDeleteParamItem(id) {
    const { model, deleteParamItem, loadPrices } = this.props;

    if (window.confirm('Вы уверены, что хотите удалить?')) {
      deleteParamItem(model.id, id);
      loadPrices();
    }
  }

  onParamItemCreate() {
    const { model, createParamItem, loadPrices } = this.props;

    createParamItem(model.id);
            loadPrices();
  }

  onFormCancel() {
    const { model, closeUpdateForm } = this.props;

    closeUpdateForm(model.id);
  }

  onFormSave(value) {
    const { model, productId, closeUpdateForm, saveParam } = this.props;

    saveParam(productId, model.id, value);

    closeUpdateForm(model.id);
  }

  onUpdateClick(e) {
    e.preventDefault();

    const { model, openUpdateForm, loadPrices } = this.props;

    openUpdateForm(model.id);
            loadPrices();
  }

  onDeleteClick(e) {
    e.preventDefault();

    const { model, deleteParam, loadPrices } = this.props;

    deleteParam(model.id);
            loadPrices();
  }
}

function mapStateToProps(state, ownProps) {
  return {
    productId: state.common.productId,
    model: state.params.params.find(item => item.id === ownProps.id),
    items: ownProps.id in state.params.paramItems ? state.params.paramItems[ownProps.id] : []
  }
}

export default connect(mapStateToProps, {
  deleteParam,
  openUpdateForm,
  closeUpdateForm,
  saveParam,
  createParamItem,
  updateParamItem,
  deleteParamItem,
  moveParamItem,
  moveParam,
  loadPrices
})(ParamContainer);
