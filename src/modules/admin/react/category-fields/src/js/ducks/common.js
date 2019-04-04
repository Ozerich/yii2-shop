import CategoryService from '../services/category';

const service = new CategoryService;

// Actions
const INIT = 'common/INIT';
const INIT_SUCCESS = 'common/INIT_SUCCESS';
const INIT_FAILURE = 'common/INIT_FAILURE';

const initialState = {
  loaded: false,
  category: null,
  categoryId: false,
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {
    case INIT:
      return Object.assign({}, state, { categoryId: action.payload.categoryId, loaded: true });
    case INIT_SUCCESS:
      return Object.assign({}, state, { model: action.payload.data.model, loaded: true });
    case INIT_FAILURE:
      return Object.assign({}, state, { error: action.error });
    default:
      return state;
  }
}

// Action Creators
export function init(categoryId) {
  return dispatch => {
    dispatch({
      type: INIT,
      payload: {
        categoryId
      }
    });

    service.get(categoryId).then(data => {
      dispatch({
        type: INIT_SUCCESS,
        payload: {
          data
        }
      });
    }).catch(err => {
      dispatch({
        type: INIT_FAILURE,
        error: err
      });
    });
  }
}
