import FieldsService from '../services/fields';

const service = new FieldsService;

const LOAD = 'fields:LOAD';
const ENABLE = 'fields:ENABLE';
const DISABLE = 'fields:DISABLE';

const START = '_START';
const SUCCESS = '_SUCCESS';
const FAILURE = '_FAILURE';

const initialState = {
  loading: false,
  loaded: false,
  error: null,

  items: [],
  activeIds: []
};

export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case LOAD + START:
      return Object.assign({}, state, { loading: true, loaded: false });
    case LOAD + FAILURE:
      return Object.assign({}, state, { loading: false, loaded: false, error: action.error });
    case LOAD + SUCCESS:
      return Object.assign({}, state, {
        loading: false,
        loaded: true,
        items: action.payload.data.collection,
        activeIds: action.payload.data.active
      });
    case ENABLE:
      return Object.assign({}, state, { activeIds: [...state.activeIds, action.payload.fieldId] });
    case DISABLE:
      return Object.assign({}, state, { activeIds: state.activeIds.filter(item => item !== action.payload.fieldId) });

    default:
      return state;
  }
}

export function load() {
  return (dispatch, getState) => {
    const categoryId = getState().common.categoryId;

    dispatch({
      type: LOAD + START
    });

    service.parents(categoryId).then(data => {
      dispatch({
        type: LOAD + SUCCESS,
        payload: {
          data
        }
      });
    }).catch(error => {
      dispatch({
        type: LOAD + FAILURE,
        error
      });
    });
  };
}

export function enable(fieldId) {
  return (dispatch, getState) => {
    const categoryId = getState().common.categoryId;

    dispatch({
      type: ENABLE,
      payload: {
        fieldId
      }
    });

    service.toggle(categoryId, fieldId, true);
  }
}

export function disable(fieldId) {
  return (dispatch, getState) => {
    const categoryId = getState().common.categoryId;

    dispatch({
      type: DISABLE,
      payload: {
        fieldId
      }
    });

    service.toggle(categoryId, fieldId, false);
  }
}