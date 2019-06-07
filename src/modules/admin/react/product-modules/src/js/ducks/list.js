import CommonService from '../services/common';

const service = new CommonService;

const _REQUEST = '_REQUEST';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const LOAD = 'list:LOAD';
const MOVE = 'list:MOVE';
const REMOVE = 'list:REMOVE';
const QUANTITY_CHANGED = 'list:QUANTITY_CHANGED';

const initialState = {
  loading: false,
  error: null,
  entities: [],
};

function stateMove(entities, id, direction) {
  let p = -1;
  for (let i = 0; i < entities.length; i++) {
    if (entities[i].id === id) {
      p = i;
      break;
    }
  }

  if (p === -1) {
    return entities;
  }

  if (p === 0 && direction === 'up') {
    return entities;
  }

  if (p === entities.length - 1 && direction === 'down') {
    return entities;
  }

  if (direction === 'up') {
    const start = entities.slice(0, p - 1);
    const items = start.concat(entities[p]).concat(entities[p - 1]);
    return items.concat(entities.slice(p + 1));
  } else {
    const start = entities.slice(0, p);
    const items = start.concat(entities[p + 1]).concat(entities[p]);
    return items.concat(entities.slice(p + 2));
  }
}

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

    case QUANTITY_CHANGED:
      return {
        ...state, entities: state.entities.map(item => {
          if (item.id !== payload.id) {
            return item;
          }
          return { ...item, quantity: payload.value };
        })
      };

    case MOVE:
      return { ...state, entities: stateMove(state.entities, payload.id, payload.direction) }

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


export function quantityChange(id, value) {
  return dispatch => {
    dispatch({
      type: QUANTITY_CHANGED,
      payload: {
        id, value
      }
    });

    service.quantity(id, value);
  };
}