import { combineReducers } from 'redux';

import common from './ducks/common';

const rootReducer = combineReducers({
  common
});

export default rootReducer;