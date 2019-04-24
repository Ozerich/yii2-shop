import { combineReducers } from 'redux';

import filter from './ducks/filter';
import list from './ducks/list';

const rootReducer = combineReducers({
  filter,
  list
});

export default rootReducer;