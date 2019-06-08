import CommonService from '../services/common';

const service = new CommonService;

// Actions
const CHANGE = 'CHANGE';
const CHANGE_MODULE = 'CHANGE_MODULE';

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const LOAD = 'LOAD';
const INIT = 'INIT';

const initialState = {
  initLoading: true,
  currencies: [],

  loading: false,
  items: []
};

export function load(request) {
  return dispatch => {
    dispatch({
      type: LOAD + _START
    });

    service.products(request).then(items => {
      dispatch({
        type: LOAD + _SUCCESS,
        payload: {
          items
        }
      });
    }).catch(error => {
      console.error(error);
      dispatch({
        type: LOAD + _FAILURE,
        payload: {
          error
        }
      })
    });
  };
}

export function change(productId, paramIds, data) {
  return dispatch => {
    service.save(productId, Array.isArray(paramIds) && paramIds.length ? paramIds[0] : null, Array.isArray(paramIds) && paramIds.length > 1 ? paramIds[1] : null, data);

    dispatch({
      type: CHANGE,
      payload: {
        productId,
        paramIds,
        data
      }
    });
  };
}

export function changeModule(productId, moduleId, data) {
  return dispatch => {
    dispatch({
      type: CHANGE_MODULE,
      payload: {
        productId, moduleId, data
      }
    });

    service.saveModule(moduleId, data.price, data.discount_mode, data.discount_value);
  };
}


// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case  INIT + _SUCCESS:
      return Object.assign({}, state, {
        initLoading: false,
        currencies: action.payload
      });

    case LOAD + _START:
      return Object.assign({}, state, { loading: true });

    case LOAD + _SUCCESS:
      return Object.assign({}, state, { loading: false, items: action.payload.items });

    case CHANGE:
      return Object.assign({}, state, {
        items: state.items.map(item => {
          if (item.id !== action.payload.productId) {
            return item;
          }

          if (!action.payload.paramIds || action.payload.paramIds.length === 0) {
            return Object.assign({}, item, { price: action.payload.data });
          }

          if (action.payload.paramIds && action.payload.paramIds.length) {
            return Object.assign({}, item, {
              children: item.children.map(subItem => {
                let found = true;
                subItem.params.forEach((param, ind) => {
                  if (ind >= action.payload.paramIds || param.id !== action.payload.paramIds[ind]) {
                    found = false;
                  }
                });

                if (!found) {
                  return subItem;
                }

                return Object.assign({}, subItem, { price: action.payload.data });
              })
            });
          }

          return item;
        })
      });

    case CHANGE_MODULE:
      return Object.assign({}, state, {
        items: state.items.map(item => {
          if (item.id !== action.payload.productId) {
            return item;
          }

          return Object.assign({}, item, {
            modules: item.modules.map(module => {
              if (module.id !== action.payload.moduleId) {
                return module;
              }

              return Object.assign({}, module, { price: action.payload.data });
            })
          });
        })
      });

    default:
      return state;
  }
}

export function loadCurrencies() {
  return dispatch => {
    service.currencies().then(data => {
      dispatch({
        type: INIT + _SUCCESS,
        payload: data
      });
    });
  };
}

export function changeProductCurrency(productId, currency) {
  return dispatch => {
    service.saveCurrency(productId, currency);
  };
}