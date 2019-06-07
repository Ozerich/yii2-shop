import { combineReducers } from 'redux';

import newModule from './ducks/new';

const rootReducer = combineReducers({
  'new': newModule
});

export default rootReducer;