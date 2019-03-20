// Actions
const INIT = 'common/INIT';

const initialState = {
  loaded: false,
  productId: false
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {
    case INIT:
      return Object.assign({}, state, { productId: action.payload.productId, loaded: true });
    default:
      return state;
  }
}

// Action Creators
export function init(productId) {
  return {
    type: INIT,
    payload: {
      productId
    }
  };
}
