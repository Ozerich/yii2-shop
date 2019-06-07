import { MODULE_MODE_SIMPLE } from "../constants/ModuleMode";
import CommonService from '../services/common';

import { load } from "./list";

const service = new CommonService;

const _REQUEST = '_REQUEST';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const OPEN = 'new:OPEN';
const CLOSE = 'new:CLOSE';
const CHANGE_MODE = 'new:CHANGE_MODE';

const CREATE = 'new:CREATE';

const initialState = {
  opened: false,
  mode: MODULE_MODE_SIMPLE,
  loading: false,
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  const payload = action.payload || {};
  const error = action.error || null;

  switch (action.type) {

    case OPEN:
      return { ...state, opened: true };

    case CLOSE:
      return { ...state, opened: false };

    case CHANGE_MODE:
      return { ...state, mode: payload.value };


    case CREATE + _REQUEST:
      return { ...state, loading: true };
    case CREATE + _SUCCESS:
      return { ...state, loading: false, opened: false };
    case CREATE + _FAILURE:
      return { ...state, loading: false };

    default:
      return state;
  }
}

export function open() {
  return {
    type: OPEN
  }
}

export function close() {
  return {
    type: CLOSE
  }
}

export function changeMode(value) {
  return {
    type: CHANGE_MODE,
    payload: {
      value
    }
  }
}

export function create(formData) {
  return (dispatch, getState) => {
    dispatch({
      type: CREATE + _REQUEST
    });

    const params = [];

    if (formData.width) {
      params.push({ param: 'Ширина', value: formData.width });
    }
    if (formData.height) {
      params.push({ param: 'Высота', value: formData.height });
    }
    if (formData.depth) {
      params.push({ param: 'Глубина', value: formData.depth });
    }

    service.createModule(
        getState().common.productId,
        formData.name,
        formData.sku,
        formData.note,
        parseFloat(formData.price),
        formData.discount,
        parseFloat(formData.discount_value),
        formData.images,
        params
    ).then(data => {
      dispatch({
        type: CREATE + _SUCCESS
      });
      dispatch(load());
    }).catch(error => {
      dispatch({
        type: CREATE + _FAILURE,
        error
      });
    });
  };
}