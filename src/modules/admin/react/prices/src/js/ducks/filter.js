import CommonService from '../services/common';

const service = new CommonService;

// Actions
const INIT = 'common/INIT';

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const initialState = {
  loading: false,
  categories: [],
  manufactures: []
};


export function init() {
  return dispatch => {
    dispatch({
      type: INIT + _START
    });

    service.init().then(payload => {
      dispatch({
        type: INIT + _SUCCESS,
        payload: payload
      });
    }).catch(error => {
      console.error(error);
      dispatch({
        type: INIT + _FAILURE,
        payload: {
          error
        }
      })
    });
  };
}

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case INIT + _START:
      return Object.assign({}, state, {
        loading: false
      });

    case INIT + _SUCCESS:
      return Object.assign({}, state, {
        categories: action.payload.categories,
        manufactures: action.payload.manufactures
      });


    default:
      return state;
  }
}
