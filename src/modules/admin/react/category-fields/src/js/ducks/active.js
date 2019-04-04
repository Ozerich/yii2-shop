import FieldService from '../services/fields';

const service = new FieldService;

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const LOAD = 'active:LOAD';
const SET_AS_ACTIVE = 'active:SET_AS_ACTIVE';
const SET_AS_INACTIVE = 'active:SET_AS_INACTIVE';

const initialState = {
  loading: false,
  loaded: false,
  items: [],
  activeIds: []
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {
    case LOAD + _START:
      return Object.assign({}, state, { loading: true });
    case LOAD + _SUCCESS:
      return Object.assign({}, state, {
        loading: false,
        loaded: true,
        items: action.payload.data.collection,
        activeIds: action.payload.data.active
      });
    case LOAD + _FAILURE:
      return Object.assign({}, state, { loading: false, loaded: false, error: action.error });
    case SET_AS_ACTIVE:
      return Object.assign({}, state, { activeIds: [...state.activeIds, action.payload.fieldId] });
    case SET_AS_INACTIVE:
      return Object.assign({}, state, { activeIds: state.activeIds.filter(item => item !== action.payload.fieldId) });
    default:
      return state;
  }
}

// Action Creators
export function load() {
  return (dispatch, getState) => {
    const categoryId = getState().common.categoryId;

    dispatch({
      type: LOAD + _START,
      payload: {
        categoryId
      }
    });

    service.parents(categoryId).then(data => {
      dispatch({
        type: LOAD + _SUCCESS,
        payload: {
          data
        }
      });
    }).catch(err => {
      dispatch({
        type: LOAD + _FAILURE,
        error: err
      });
    });
  }
}

export function setAsActive(fieldId) {
  return (dispatch, getState) => {
    dispatch({
      type: SET_AS_ACTIVE,
      payload: {
        fieldId
      }
    });

    service.toggle(getState().common.categoryId, fieldId, true);
  }
}

export function setAsInActive(fieldId) {
  return (dispatch, getState) => {
    dispatch({
      type: SET_AS_INACTIVE,
      payload: {
        fieldId
      }
    });

    service.toggle(getState().common.categoryId, fieldId, false);
  }
}