import { combineReducers } from 'redux';

import form from './ducks/form';
import list from './ducks/list';

const rootReducer = combineReducers({
  form,
  list
});

export default rootReducer;