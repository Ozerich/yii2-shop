import { combineReducers } from 'redux';

import paramsForm from './ducks/params-form';
import params from './ducks/params';
import common from './ducks/common';

const rootReducer = combineReducers({
  paramsForm,
  params,
  common
});

export default rootReducer;