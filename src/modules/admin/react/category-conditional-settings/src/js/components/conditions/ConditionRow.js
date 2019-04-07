import React, { Component } from 'react';
import { connect } from 'react-redux';

import ConditionRowFilter from './ConditionRowFilter';
import ConditionRowCompare from './ConditionRowCompare';
import ConditionRowValue from './ConditionRowValue';
import ConditionRowActions from './ConditionRowActions';

class ConditionRow extends Component {
  render() {
    const { model } = this.props;

    return (
        <tr>
          <td className="cell-filter">
            <ConditionRowFilter model={model} />
          </td>
          <td className="cell-compare">
            <ConditionRowCompare model={model} />
          </td>
          <td>
            <ConditionRowValue model={model} />
          </td>
          <td className="cell-actions">
            <ConditionRowActions model={model} />
          </td>
        </tr>
    );
  }
}

function mapStateToProps(state, ownProps) {
  return {};
}

export default connect(mapStateToProps, {})(ConditionRow);