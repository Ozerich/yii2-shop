import FieldService from '../services/fields';

const service = new FieldService;

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';
const DELETE = 'groups/DELETE';

// Actions
const LOAD = 'fields/LOAD';

const initialState = {
  loading: false,
  loaded: false,
  error: null,
  entities: []
};


// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case DELETE + _SUCCESS:
      return Object.assign({}, state, { entities: state.entities.filter(item => item.id !== action.payload.id) });

    case LOAD + _START:
      return Object.assign({}, state, { loading: true, loaded: false });

    case LOAD + _FAILURE:
      return Object.assign({}, state, { loading: false, error: action.error });

    case LOAD + _SUCCESS:
      return Object.assign({}, state, {
        loading: false,
        loaded: true,
        entities: action.payload.items
      });

    default:
      return state;
  }
};

export function loadAll(categoryId) {
  return dispatch => {
    dispatch({
      type: LOAD + _START
    });

    service.all(categoryId).then(data => {
      dispatch({
        type: LOAD + _SUCCESS,
        payload: {
          items: data.collection
        }
      });
    }).catch(error => {
      dispatch({
        type: LOAD + _FAILURE,
        error
      });
    });
  }
}


export function deleteField(groupId) {
  return dispatch => {
    dispatch({
      type: DELETE + _START
    });

    service.deleteField(groupId).then(data => {
      dispatch({
        type: DELETE + _SUCCESS,
        payload: {
          id: groupId
        }
      });
    }).catch(error => {
      dispatch({
        type: DELETE + _FAILURE,
        error
      });
    });
  }
}