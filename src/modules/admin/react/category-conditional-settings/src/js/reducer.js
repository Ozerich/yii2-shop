import { combineReducers } from 'redux';

import common from './ducks/common';
import conditions from './ducks/conditions';
import fields from './ducks/fields';

const rootReducer = combineReducers({
  common,
  fields,
  conditions
});

export default rootReducer;