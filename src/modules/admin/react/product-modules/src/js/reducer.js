import { combineReducers } from 'redux';

import common from './ducks/common';
import list from './ducks/list';
import newModule from './ducks/new';

const rootReducer = combineReducers({
  common,
  list,
  'new': newModule
});

export default rootReducer;