import { combineReducers } from 'redux';

import common from './ducks/common';
import groups from './ducks/groups';
import fields from './ducks/fields';

const rootReducer = combineReducers({
  common,
  groups,
  fields,
});

export default rootReducer;