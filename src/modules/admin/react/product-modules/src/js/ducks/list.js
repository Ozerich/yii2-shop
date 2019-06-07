import CommonService from '../services/common';

const service = new CommonService;

const _REQUEST = '_REQUEST';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const LOAD = 'list:LOAD';
const MOVE = 'list:MOVE';
const REMOVE = 'list:REMOVE';

const initialState = {
  loading: false,
  error: null,
  entities: [],


};

// Reducer
export default function reducer(state = initialState, action = {}) {
  const payload = action.payload || {};
  const error = action.error || null;

  switch (action.type) {

    case LOAD + _REQUEST:
      return { ...state, loading: true };
    case LOAD + _SUCCESS:
      return { ...state, loading: false, entities: payload };
    case LOAD + _FAILURE:
      return { ...state, loading: false, error: error };


    case REMOVE + _SUCCESS:
      return { ...state, entities: state.entities.filter(model => payload.id !== model.id) };

    default:
      return state;
  }
}

export function load() {
  return (dispatch, getState) => {
    dispatch({
      type: LOAD + _REQUEST
    });

    service.list(getState().common.productId).then(data => {
      dispatch({
        type: LOAD + _SUCCESS,
        payload: data
      })
    }).catch(error => {
      dispatch({
        type: LOAD + _FAILURE,
        error
      });
    });
  };
}

export function remove(moduleId) {
  return dispatch => {
    dispatch({
      type: REMOVE + _REQUEST,
      payload: {
        id: moduleId
      }
    });

    service.remove(moduleId).then(() => {
      dispatch({
        type: REMOVE + _SUCCESS,
        payload: {
          id: moduleId
        }
      });
    }).catch(error => {
      dispatch({
        type: REMOVE + _FAILURE,
        error: error,
        payload: {
          id: moduleId
        }
      });
    });
  };
}

export function move(id, direction) {
  return dispatch => {
    dispatch({
      type: MOVE,
      payload: {
        id, direction
      }
    });

    service.move(id, direction);
  };
}