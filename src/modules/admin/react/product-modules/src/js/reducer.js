import { combineReducers } from 'redux';

import common from './ducks/common';
import newModule from './ducks/new';

const rootReducer = combineReducers({
  common,
  'new': newModule
});

export default rootReducer;