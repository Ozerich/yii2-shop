import GroupService from '../services/groups';

import { loadAll } from './groups';

const service = new GroupService;

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

// Actions
const SHOW = 'group-form/SHOW';
const HIDE = 'group-form/HIDE';
const SAVE = 'group-form/SAVE';

const initialState = {
  modelId: null,
  opened: false,
  loading: false,
  error: null,
};


// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case SHOW:
      return Object.assign({}, state, { opened: true, modelId: action.payload.id });

    case HIDE:
      return Object.assign({}, state, { opened: false });

    case SAVE + _START:
      return Object.assign({}, state, { loading: true });

    case SAVE + _FAILURE:
      return Object.assign({}, state, { loading: false, error: action.error });

    case SAVE + _SUCCESS:
      return Object.assign({}, state, {
        loading: false
      });

    default:
      return state;
  }
};

export function showForm(id = null) {
  return {
    type: SHOW,
    payload: {
      id
    }
  }
}

export function hideForm() {
  return {
    type: HIDE
  }
}

export function create(name) {
  return (dispatch, getState) => {
    const categoryId = getState().common.categoryId;

    dispatch({
      type: SAVE + _START
    });

    service.create(categoryId, name).then(() => {
      dispatch({
        type: SAVE + _SUCCESS
      });

      dispatch(hideForm());

      dispatch(loadAll(categoryId));
    }).catch(error => {
      dispatch({
        type: SAVE + _FAILURE,
        error
      });
    });
  }
}


export function save(groupId, name) {
  return (dispatch, getState) => {
    const categoryId = getState().common.categoryId;

    dispatch({
      type: SAVE + _START
    });

    service.update(groupId, name).then(() => {
      dispatch({
        type: SAVE + _SUCCESS
      });

      dispatch(hideForm());

      dispatch(loadAll(categoryId));
    }).catch(error => {
      dispatch({
        type: SAVE + _FAILURE,
        error
      });
    });
  }
}