import React, { Component } from 'react';
import { connect } from 'react-redux';
import Section from "../components/Section";
import { add, load, save } from "../ducks/conditions";

import ConditionRow from '../components/conditions/ConditionRow';

class Conditions extends Component {
  componentWillMount() {
    this.props.load();
  }

  render() {
    const { loading, saveLoading, items, savedNoteVisible } = this.props;
    return (
        <Section title="Условия"
                 innerLoading={saveLoading}
                 loading={loading}
                 buttonLabel="Сохранить условия"
                 buttonNote={savedNoteVisible ? "Сохранено" : null}
                 onButtonClick={this.onSaveClick.bind(this)}>
          <table>
            <thead>
            <tr>
              <th className="cell-filter">Фильтр</th>
              <th className="cell-compare">Условие</th>
              <th>Значение</th>
              <th className="cell-actions">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            {items.map(item => <ConditionRow key={item.id} model={item} />)}
            </tbody>
          </table>
          <div className="button-wrapper">
            <button className="btn btn-mini btn-success" onClick={this.onAddConditionClick.bind(this)}>Добавить
              условие
            </button>
          </div>
        </Section>
    );
  }

  onAddConditionClick() {
    this.props.add();
  }

  onSaveClick() {
    this.props.save();
  }
}

function mapStateToProps(state) {
  return state.conditions;
}

export default connect(mapStateToProps, { load, add, save })(Conditions);