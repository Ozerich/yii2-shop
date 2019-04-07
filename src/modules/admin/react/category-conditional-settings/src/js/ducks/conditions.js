import ConditionsService from '../services/conditions';

const service = new ConditionsService;

const LOAD = 'conditions:LOAD';
const SAVE = 'conditions:SAVE';
const ADD = 'conditions:ADD';
const REMOVE = 'conditions:REMOVE';

const CHANGE_CONDITION_FIELD = 'conditions:CHANGE_CONDITION_FIELD';

const START = '_START';
const SUCCESS = '_SUCCESS';
const FAILURE = '_FAILURE';

const initialState = {
  loading: false,
  loaded: false,
  error: null,

  categories: [],
  items: [],

  saveLoading: false,
  savedNoteVisible: false,
};

function createNewModel() {
  return {
    id: Math.ceil(Math.random() * 10000000),
    filter: null,
    compare: null,
    value: null
  };
}

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
        categories: action.payload.categories,
        items: action.payload.data.collection
      });

    case SAVE + START:
      return Object.assign({}, state, { saveLoading: true, savedNoteVisible: false });
    case SAVE + FAILURE:
      return Object.assign({}, state, { saveLoading: false });
    case SAVE + SUCCESS:
      return Object.assign({}, state, {
        saveLoading: false,
        savedNoteVisible: true
      });

    case ADD:
      return Object.assign({}, state, {
        items: [...state.items, createNewModel()]
      });

    case REMOVE:
      return Object.assign({}, state, {
        items: state.items.filter(item => item.id !== action.payload.id)
      });

    case CHANGE_CONDITION_FIELD:
      return Object.assign({}, state, {
        items: state.items.map(item => {
          if (item.id !== action.payload.id) {
            return item;
          }

          return Object.assign({}, item, { [action.payload.field]: action.payload.value });
        })
      })

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

    service.categories(categoryId).then(categories => {
      service.all(categoryId).then(data => {
        dispatch({
          type: LOAD + SUCCESS,
          payload: {
            categories,
            data
          }
        });
      });
    }).catch(error => {
      dispatch({
        type: LOAD + FAILURE,
        error
      });
    });
  };
}

export function add() {
  return {
    type: ADD
  };
}

export function remove(id) {
  return {
    type: REMOVE,
    payload: {
      id
    }
  };
}

export function changeFilter(id, value) {
  return {
    type: CHANGE_CONDITION_FIELD,
    payload: {
      field: 'filter',
      id, value
    }
  }
}

export function changeCompare(id, value) {
  return {
    type: CHANGE_CONDITION_FIELD,
    payload: {
      field: 'compare',
      id, value
    }
  }
}

export function changeValue(id, value) {
  return {
    type: CHANGE_CONDITION_FIELD,
    payload: {
      field: 'value',
      id, value
    }
  }
}


function getSaveData(state) {
  return {
    conditions: state.conditions.items.filter(item => !!item.filter && !!item.compare).map(item => {
      return {
        filter: (item.filter === 'PRICE' || item.filter === 'CATEGORY') ? item.filter : +item.filter,
        compare: item.compare,
        value: item.value
      };
    })
  }
}

export function save() {
  return (dispatch, getState) => {
    const categoryId = getState().common.categoryId;

    const data = getSaveData(getState());

    dispatch({
      type: SAVE + START
    });

    service.save(categoryId, data).then(data => {
      dispatch({
        type: SAVE + SUCCESS,
        payload: {
          data
        }
      });
    }).catch(error => {
      dispatch({
        type: SAVE + FAILURE,
        error
      });
    });
  };
}