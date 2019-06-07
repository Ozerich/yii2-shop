import { MODULE_MODE_SIMPLE } from "../constants/ModuleMode";

const OPEN = 'new:OPEN';
const CLOSE = 'new:CLOSE';
const CHANGE_MODE = 'new:CHANGE_MODE';

const initialState = {
  opened: false,
  mode: MODULE_MODE_SIMPLE
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