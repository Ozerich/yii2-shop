import CommonService from '../services/common';

const service = new CommonService;

// Actions
const INIT = 'common/INIT';

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const SAVE = 'common:SAVE';
const LOAD = 'common:LOAD';

const SHOW_SUCCESS = 'common:SHOW_SUCCESS';
const HIDE_SUCCESS = 'common:HIDE_SUCCESS';

const ENABLE_EXTENDED = 'common:ENABLE_EXTENDED';
const DISABLE_EXTENDED = 'common:DISABLE_EXTENDED';

const initialState = {
  loading: false,
  loaded: false,
  productId: false,

  isExtendedMode: false,

  successNoteVisible: false,

  model: null
};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {
    case INIT:
      return Object.assign({}, state, { productId: action.payload.productId });

    case ENABLE_EXTENDED:
      return Object.assign({}, state, { isExtendedMode: true });

    case DISABLE_EXTENDED:
      return Object.assign({}, state, { isExtendedMode: false });

    case SHOW_SUCCESS:
      return Object.assign({}, state, { successNoteVisible: true });
    case HIDE_SUCCESS:
      return Object.assign({}, state, { successNoteVisible: false });

    case LOAD + _START:
      return Object.assign({}, state, { loading: true, loaded: false });
    case LOAD + _FAILURE:
      return Object.assign({}, state, { loading: false });
    case LOAD + _SUCCESS:
      return Object.assign({}, state, {
        loading: false,
        loaded: true,
        model: action.payload.data.model,
        isExtendedMode: action.payload.data.model.is_extended_mode
      });

    default:
      return state;
  }
}

// Action Creators
export function init(productId) {
  return {
    type: INIT,
    payload: {
      productId
    }
  };
}

function showSuccess() {
  return {
    type: SHOW_SUCCESS
  };
}

function hideSuccess() {
  return {
    type: HIDE_SUCCESS
  };
}

export function enableExtendedMode() {
  return (dispatch, getState) => {
    const productId = getState().common.productId;

    dispatch({
      type: ENABLE_EXTENDED
    });

    service.enableExtendedMode(productId);
  };
}

export function disableExtendedMode() {
  return (dispatch, getState) => {
    const productId = getState().common.productId;

    dispatch({
      type: DISABLE_EXTENDED
    });

    service.disableExtendedMode(productId);
  };
}

export function load(productId) {
  return (dispatch) => {
    dispatch({
      type: LOAD + _START
    });

    service.load(productId).then(data => {
      dispatch({
        type: LOAD + _SUCCESS,
        payload: { data }
      });
    }).catch(error => {
      dispatch({
        type: LOAD + _FAILURE
      });
    });
  };
}

const successTimer = null;

export function save(price, isPriceDisabled, priceDisabledText, discountMode, discountValue, stock, stockDays, priceNote, isPriceFrom) {
  return (dispatch, getState) => {
    dispatch({
      type: SAVE + _START
    });

    service.save(getState().common.productId, {
      price: +price,
      disabled: !!isPriceDisabled,
      disabled_text: priceDisabledText,
      discount_mode: discountMode,
      discount_value: discountValue,
      stock: stock,
      stock_waiting_days: stockDays,
      price_note: priceNote,
      is_price_from: !!isPriceFrom
    }).then(data => {
      dispatch({
        type: SAVE + _SUCCESS
      });

      if (successTimer) {
        clearTimeout(successTimer);
      }

      dispatch(showSuccess());

      setTimeout(() => {
        dispatch(hideSuccess());
      }, 2000);

    }).catch(data => {
      dispatch({
        type: SAVE + _FAILURE
      });
    });
  };
}
