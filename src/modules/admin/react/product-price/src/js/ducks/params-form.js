// Actions
const OPEN_CREATE_FORM = 'params/OPEN_CREATE_FORM';
const CLOSE_CREATE_FORM = 'params/CLOSE_CREATE_FORM';

const initialState = {
  formOpened: false
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {
    case OPEN_CREATE_FORM:
      return Object.assign({}, state, { formOpened: true });
    case CLOSE_CREATE_FORM:
      return Object.assign({}, state, { formOpened: false });
    default:
      return state;
  }
}

// Action Creators
export function openCreateForm() {
  return {
    type: OPEN_CREATE_FORM
  };
}

export function closeCreateForm() {
  return {
    type: CLOSE_CREATE_FORM
  };
}
