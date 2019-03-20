// Actions
const INIT = 'common/INIT';

const initialState = {
  loaded: false,
  categoryId: false
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {
    case INIT:
      return Object.assign({}, state, { categoryId: action.payload.categoryId, loaded: true });
    default:
      return state;
  }
}

// Action Creators
export function init(categoryId) {
  return {
    type: INIT,
    payload: {
      categoryId
    }
  };
}
