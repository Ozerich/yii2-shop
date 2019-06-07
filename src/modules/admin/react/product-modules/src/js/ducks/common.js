const INIT = 'common:INIT';

const initialState = {
  productId: null
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  const payload = action.payload || {};

  switch (action.type) {

    case INIT:
      return { ...state, productId: payload.productId };

    default:
      return state;
  }
}

export function init(productId) {
  return {
    type: INIT,
    payload: {
      productId
    }
  }
}