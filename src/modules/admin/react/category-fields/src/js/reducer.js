import { combineReducers } from 'redux';

import active from './ducks/active';
import common from './ducks/common';
import fields from './ducks/fields';
import fieldForm from './ducks/field-form';

const rootReducer = combineReducers({
  common,
  active,
  fields, fieldForm
});

export default rootReducer;