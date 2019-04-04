import React, { Component } from 'react';
import { connect } from 'react-redux';
import Section from "../components/Section";

import FieldRow from '../components/fields/FieldRow';

import { load } from "../ducks/fields";

class Fields extends Component {
  componentWillMount() {
    this.props.load();
  }

  render() {
    const { loading, items } = this.props;

    return (
        <Section title="Поля фильтров" loading={loading}>
          {items.map(item => <FieldRow model={item} />)}
        </Section>
    );
  }
}

function mapStateToProps(state) {
  return state.fields;
}

export default connect(mapStateToProps, { load })(Fields);