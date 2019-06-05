import CommonService from '../services/common';

const service = new CommonService;

const _REQUEST = '_REQUEST';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const SUBMIT = 'list:SUBMIT';
const INIT = 'list:INIT';

const initialState = {
  visible: false,

  fields: [],

  loading: false,
  error: null,
  items: []
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case INIT:
      return { ...state, fields: action.payload.fields };


    case SUBMIT + _REQUEST:
      return { ...state, loading: true, visible: true };
    case SUBMIT + _SUCCESS:
      return { ...state, loading: false, items: action.payload };
    case SUBMIT + _FAILURE:
      return { ...state, loading: false, error: action.error };

    default:
      return state;
  }
}

export function init(fields) {
  return {
    type: INIT,
    payload: {
      fields
    }
  }
}


export function submit(categoryId, fieldIds) {
  return (dispatch, getState) => {
    dispatch({
      type: SUBMIT + _REQUEST,
    });

    service.submit(categoryId, fieldIds).then(data => {

      dispatch(init(getState().form.fields.filter(item => getState().form.selected.indexOf(item.id) !== -1)));

      dispatch({
        type: SUBMIT + _SUCCESS,
        payload: data
      });
    }).catch(error => {
      dispatch({
        type: SUBMIT + _FAILURE,
        error
      })
    });
  };
}

export function update(productId, fieldId, value) {
  return dispatch => {
    service.update(productId, fieldId, value);
  };
}