import { combineReducers } from 'redux';

import common from './ducks/common';
import fields from './ducks/fields';

const rootReducer = combineReducers({
  common,
  fields
});

export default rootReducer;