import React, { Component } from 'react';
import { connect } from "react-redux";
import ListViewRowField from "./ListViewRowField";

class ListViewRow extends Component {
  render() {
    const { model, fields } = this.props;

    return (
        <tr>
          <td className="cell-name"><a href={"/admin/products/update/" + model.id} target="_blank">{model.name}</a></td>
          <td className="cell-name">{model.manufacture}</td>
          {fields.map(field => <td><ListViewRowField key={field.id} field={field} model={model} /></td>)}
        </tr>
    );
  }
}

function mapStateToProps(state) {
  return {
    fields: state.list.fields
  }
}

export default connect(mapStateToProps)(ListViewRow);