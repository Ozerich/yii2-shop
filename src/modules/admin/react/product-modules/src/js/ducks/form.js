import CommonService from '../services/common';

const service = new CommonService;

const _REQUEST = '_REQUEST';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const CATEGORIES = 'CATEGORIES';
const FIELDS = 'FIELDS';

const TOGGLE = 'TOGGLE';
const initialState = {
  loading: false,
  error: null,
  tree: null,

  fieldsLoading: true,
  fieldsError: null,
  fields: [],

  selected: [],
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case CATEGORIES + _REQUEST:
      return { ...state, loading: true };
    case CATEGORIES + _SUCCESS:
      return { ...state, loading: false, tree: action.payload };
    case CATEGORIES + _FAILURE:
      return { ...state, loading: false, error: action.error };

    case FIELDS + _REQUEST:
      return { ...state, fieldsLoading: true, selected: [] };
    case FIELDS + _SUCCESS:
      return { ...state, fieldsLoading: false, fields: action.payload };
    case FIELDS + _FAILURE:
      return { ...state, fieldsLoading: false, fieldsError: action.error };

    case TOGGLE:
      return action.payload.checked ?
          { ...state, selected: [...state.selected, action.payload.fieldId] } :
          { ...state, selected: state.selected.filter(id => id !== action.payload.fieldId) };

    default:
      return state;
  }
}

export function init() {
  return dispatch => {
    dispatch({
      type: CATEGORIES + _REQUEST
    });

    service.tree().then(data => {
      dispatch({
        type: CATEGORIES + _SUCCESS,
        payload: data
      })
    }).catch(err => {
      dispatch({
        type: CATEGORIES + _FAILURE,
        error: err
      });
    });
  };
}

export function fields(categoryId) {
  return (dispatch) => {
    dispatch({
      type: FIELDS + _REQUEST
    });

    service.fields(categoryId).then(data => {
      dispatch({
        type: FIELDS + _SUCCESS,
        payload: data.collection
      });
    }).catch(err => {
      dispatch({
        type: FIELDS + _FAILURE,
        error: err
      });
    });
  };
}

export function toggle(fieldId, checked) {
  return {
    type: TOGGLE,
    payload: {
      fieldId,
      checked
    }
  }
}