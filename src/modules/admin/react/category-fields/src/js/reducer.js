import { combineReducers } from 'redux';

import common from './ducks/common';
import groups from './ducks/groups';
import groupForm from './ducks/group-form';
import fields from './ducks/fields';
import fieldForm from './ducks/field-form';

const rootReducer = combineReducers({
  common,
  groups, groupForm,
  fields, fieldForm
});

export default rootReducer;