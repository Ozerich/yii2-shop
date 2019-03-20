import { combineReducers } from 'redux';

import common from './ducks/common';
import groups from './ducks/groups';
import groupForm from './ducks/group-form';
import fields from './ducks/fields';

const rootReducer = combineReducers({
  common,
  groups, groupForm,
  fields,
});

export default rootReducer;