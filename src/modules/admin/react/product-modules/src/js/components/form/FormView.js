import React, { Component } from 'react';
import { connect } from 'react-redux';
import FormSelect from "../Formik/FormSelect";
import FormViewFields from "./FormViewFields";

class FormView extends Component {
  render() {
    const { values, handleChange, categories, selected } = this.props;

    if (!categories) {
      return null;
    }

    return (
        <>
          <div className="row">
            <div className="col-xs-6">
              <FormSelect id="category_id" name="category_id" label="Категория" handleChange={handleChange}
                          emptyValue="Выберите категорию"
                          items={categories} />
            </div>
          </div>
          {values.category_id ? (
              <>
                <div className="row">
                  <div className="col-xs-12">
                    <FormViewFields categoryId={values.category_id} />
                  </div>
                </div>
                <hr />
                {selected.length > 0 ? <div className="row">
                  <div className="col-xs-12">
                    <button className="btn btn-success">Показать</button>
                  </div>
                </div> : null}
              </>
          ) : null}
        </>
    )
  }
}

function mapStateToProps(state) {
  return {
    categories: state.form.tree,
    selected: state.form.selected,
  };
}

export default connect(mapStateToProps)(FormView);