import GroupService from '../services/groups';

const service = new GroupService;

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

// Actions
const LOAD = 'groups/LOAD';

const initialState = {
  loading: false,
  loaded: false,
  error: null,
  entities: []
};


// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

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
    return new Promise(resolve => {
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
        resolve(data.collection);
      }).catch(error => {
        dispatch({
          type: LOAD + _FAILURE,
          error
        });
      });
    });
  }
}