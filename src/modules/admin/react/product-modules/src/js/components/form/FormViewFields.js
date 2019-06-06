import React, { Component } from 'react';
import { connect } from 'react-redux';
import BlockOrLoader from "../ui/BlockOrLoader";

import { fields, toggle } from "../../ducks/form";
import FormCheckbox from "../Formik/FormCheckbox";

class FormViewFields extends Component {

  getRootCategoryId(categoryId) {
    const { tree } = this.props;

    for (let i = 0; i < tree.length; i++) {
      if (tree[i].id === +categoryId) {
        if (tree[i].parent_id) {
          return this.getRootCategoryId(tree[i].parent_id);
        } else {
          return categoryId;
        }
      }
    }

    return categoryId;
  }

  componentWillReceiveProps(nextProps, nextContext) {
    if (this.getRootCategoryId(nextProps.categoryId) !== this.getRootCategoryId(this.props.categoryId)) {
      this.load(this.getRootCategoryId(nextProps.categoryId));
    }
  }

  componentWillMount() {
    this.load(this.getRootCategoryId(this.props.categoryId));
  }

  load(categoryId) {
    this.props.fields(categoryId);
  }

  render() {
    const { loading, fieldItems } = this.props;

    return (
        <BlockOrLoader loading={loading}>
          {fieldItems.map(model => this.renderField(model))}
        </BlockOrLoader>
    );
  }

  renderField(model) {
    return (
        <div className="row">
          <div className="col-xs-12" key={model.id}>
            <FormCheckbox label={model.name} id={"check_" + model.id}
                          checked={this.props.selected.indexOf(model.id) !== -1}
                          onChange={value => this.props.toggle(model.id, value)} />
          </div>
        </div>
    );
  }
}

function mapStateToProps(state) {
  return {
    loading: state.form.fieldsLoading,
    fieldItems: state.form.fields,
    selected: state.form.selected,
    tree: state.form.tree
  };
}

export default connect(mapStateToProps, { fields, toggle })(FormViewFields);