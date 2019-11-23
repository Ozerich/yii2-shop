import ParamsService from '../services/params';
import ParamItemsService from '../services/param-items';

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

// Actions
const LOAD = 'params/LOAD';
const CREATE_PARAM = 'params/CREATE_PARAM';
const OPEN_UPDATE_FORM = 'params/OPEN_UPDATE_FORM';
const CLOSE_UPDATE_FORM = 'params/CLOSE_UPDATE_FORM';
const DELETE_PARAM = 'params/DELETE_PARAM';
const MOVE_PARAM = 'params/MOVE_PARAM';
const SAVE_PARAM = 'params/SAVE_PARAM';

const GET_PARAM_ITEMS = 'params/GET_PARAM_ITEMS';
const CREATE_PARAM_ITEM = 'params/CREATE_PARAM_ITEM';
const UPDATE_PARAM_ITEM = 'params/UPDATE_PARAM_ITEM';
const DELETE_PARAM_ITEM = 'params/DELETE_PARAM_ITEM';
const MOVE_PARAM_ITEM = 'params/MOVE_PARAM_ITEM';

const GET_PRICES = 'params/GET_PRICES';
const SAVE_PRICE = 'params/SAVE_PRICE';

const TOGGLE_DISCOUNT = 'params/TOGGLE_DISCOUNT';

const paramService = new ParamsService;
const paramItemsService = new ParamItemsService;

const initialState = {
  loading: false,
  error: null,

  params: [],
  paramItems: {},

  pricesLoading: false,
  prices: []
};

function getPriceKey(valueFirstId, valueSecondId) {
  if (!valueSecondId) {
    return valueFirstId;
  }
  return valueFirstId + 'x' + valueSecondId;
}

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {

    case LOAD + _START:
      return Object.assign({}, state, { loading: true });

    case LOAD + _FAILURE:
      return Object.assign({}, state, { loading: false, error: action.error });

    case LOAD + _SUCCESS:
      return Object.assign({}, state, {
        loading: false, params: action.payload.items.map(item => {
          return Object.assign(item, {
            itemsLoading: false,
            formOpened: false,
          });
        })
      });

    case CREATE_PARAM + _SUCCESS:
      return Object.assign({}, state, {
        params: [...state.params, {
          id: action.payload.model.id,
          name: action.payload.model.name,
          formOpened: false,
        }]
      });

    case SAVE_PARAM + _SUCCESS:
      return Object.assign({}, state, {
        params: state.params.map(item => {
          if (item.id !== action.payload.model.id) {
            return item;
          }
          return Object.assign({}, item, { name: action.payload.model.name });
        })
      });

    case OPEN_UPDATE_FORM:
      return Object.assign({}, state, {
        params: state.params.map(item => {
          if (item.id !== action.payload.id) {
            return item;
          }
          return Object.assign({}, item, { formOpened: true });
        })
      });

    case CLOSE_UPDATE_FORM:
      return Object.assign({}, state, {
        params: state.params.map(item => {
          if (item.id !== action.payload.id) {
            return item;
          }
          return Object.assign({}, item, { formOpened: false });
        })
      });

    case DELETE_PARAM:
      return Object.assign({}, state, {
        params: state.params.filter(item => {
          return item.id !== action.payload.id;
        })
      });

    case CREATE_PARAM_ITEM:

      const newObject = {
        id: 100000 + Math.ceil(Math.random() * 1000000),
        isNew: true,
        loading: false,
        serverId: null,
        model: {
          name: '',
          description: ''
        }
      };

      const newValue = action.payload.paramId in state.paramItems === false ? [newObject] : [...state.paramItems[action.payload.paramId], newObject];

      return Object.assign({}, state, {
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: newValue
        })
      });

    case UPDATE_PARAM_ITEM:
      return Object.assign({}, state, {
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: state.paramItems[action.payload.paramId].map(item => {
            if (item.id !== action.payload.itemId) {
              return item;
            }
            return Object.assign({}, item, {
              model: action.payload.model
            });
          })
        })
      });

    case CREATE_PARAM_ITEM + _START:
      return Object.assign({}, state, {
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: state.paramItems[action.payload.paramId].map(item => {
            if (item.id !== action.payload.itemId) {
              return item;
            }

            return Object.assign({}, item, {
              loading: true
            });
          })
        })
      });

    case CREATE_PARAM_ITEM + _FAILURE:
      return Object.assign({}, state, {
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: state.paramItems[action.payload.paramId].map(item => {
            if (item.id !== action.payload.itemId) {
              return item;
            }

            return Object.assign({}, item, {
              loading: false
            });
          })
        })
      });

    case CREATE_PARAM_ITEM + _SUCCESS:
      return Object.assign({}, state, {
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: state.paramItems[action.payload.paramId].map(item => {
            if (item.id !== action.payload.oldId) {
              return item;
            }

            return Object.assign({}, item, {
              serverId: action.payload.model.id,
              loading: false,
              isNew: false,
            });
          })
        })
      });


    case DELETE_PARAM_ITEM:
      const { id, paramId } = action.payload;

      return Object.assign({}, state, {
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: state.paramItems[action.payload.paramId].filter(item => item.id !== id)
        })
      });

    case MOVE_PARAM_ITEM + _START:
      const { itemId, direction } = action.payload;

      let replaceWithId = null;

      for (let i = 0; i < state.paramItems[action.payload.paramId].length; i++) {
        if (state.paramItems[action.payload.paramId][i].id === itemId) {
          if (direction === -1 && i > 0) {
            replaceWithId = state.paramItems[action.payload.paramId][i - 1].id;
          } else if (direction === 1 && i < state.paramItems[action.payload.paramId].length - 1) {
            replaceWithId = state.paramItems[action.payload.paramId][i + 1].id;
          }
        }
      }

      if (!replaceWithId) {
        return state;
      }

      const item1 = state.paramItems[action.payload.paramId].find(item => item.id === itemId);
      const item2 = state.paramItems[action.payload.paramId].find(item => item.id === replaceWithId);

      return Object.assign({}, state, {
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: state.paramItems[action.payload.paramId].map(item => {
            if (item1.id === item.id) {
              return Object.assign({}, item2);
            } else if (item2.id === item.id) {
              return Object.assign({}, item1);
            }
            return item;
          })
        })
      });

    case GET_PARAM_ITEMS + _START:
      return Object.assign({}, state, {
        params: state.params.map(item => {
          if (item.id !== action.payload.paramId) {
            return item;
          }
          return Object.assign({}, item, { itemsLoading: true });
        })
      });

    case GET_PARAM_ITEMS + _SUCCESS:
      return Object.assign({}, state, {
        params: state.params.map(item => {
          if (item.id !== action.payload.paramId) {
            return item;
          }
          return Object.assign({}, item, { itemsLoading: false });
        }),
        paramItems: Object.assign({}, state.paramItems, {
          [action.payload.paramId]: action.payload.data.map(item => {
            return {
              id: item.id,
              isNew: false,
              loading: false,
              serverId: item.id,
              model: item
            }
          })
        })
      });

    case GET_PARAM_ITEMS + _FAILURE :
      return Object.assign({}, state, {
        params: state.params.map(item => {
          if (item.id !== action.payload.paramId) {
            return item;
          }
          return Object.assign({}, item, { itemsLoading: false });
        })
      });

    case GET_PRICES + _START:
      return Object.assign({}, state, {
        pricesLoading: true
      });

    case GET_PRICES + _FAILURE :
      return Object.assign({}, state, {
        pricesLoading: false
      });

    case GET_PRICES + _SUCCESS:
      const obj = {};
      action.payload.items.forEach(model => obj[getPriceKey(model.value_id, model.second_value_id)] = Object.assign({}, model, {
        has_discount: model.discount_mode !== null
      }));

      return Object.assign({}, state, {
        pricesLoading: false,
        prices: obj
      });

    case SAVE_PRICE + _START:
      let priceKey = getPriceKey(action.payload.firstParamId, action.payload.secondParamId);
      if(priceKey in state.prices === false && action.payload.secondParamId){
          priceKey = getPriceKey(action.payload.secondParamId, action.payload.firstParamId);
      }

      return Object.assign({}, state, {
        prices: Object.assign({}, state.prices, {
          [priceKey]: Object.assign({}, state.prices[priceKey], action.payload.value)
        })
      });

    case TOGGLE_DISCOUNT:
      return Object.assign({}, state, {
        prices: Object.assign({}, state.prices, {
          [getPriceKey(action.payload.firstParamId, action.payload.secondParamId)]:
            Object.assign({}, state.prices[getPriceKey(action.payload.firstParamId, action.payload.secondParamId)], { has_discount: action.payload.enabled })
        })
      });

    case MOVE_PARAM:
      let replaceWithParamId = null;

      for (let i = 0; i < state.params.length; i++) {
        if (state.params[i].id === action.payload.id) {
          if (action.payload.direction === -1 && i > 0) {
            replaceWithParamId = state.params[i - 1].id;
          } else if (action.payload.direction === 1 && i < state.params.length - 1) {
            replaceWithParamId = state.params[i + 1].id;
          }
        }
      }

      if (!replaceWithParamId) {
        return state;
      }

      const paramItem1 = state.params.find(item => item.id === action.payload.id);
      const paramItem2 = state.params.find(item => item.id === replaceWithParamId);

      return Object.assign({}, state, {
        params: state.params.map(item => {
          if (paramItem1.id === item.id) {
            return Object.assign({}, paramItem2);
          } else if (paramItem2.id === item.id) {
            return Object.assign({}, paramItem1);
          }
          return item;
        })
      });

    default:
      return state;
  }
}

// Action Creators

export function openUpdateForm(id) {
  return {
    type: OPEN_UPDATE_FORM,
    payload: {
      id
    }
  }
}

export function closeUpdateForm(id) {
  return {
    type: CLOSE_UPDATE_FORM,
    payload: {
      id
    }
  }
}

export function deleteParam(id) {
  return dispatch => {

    paramService.deleteParam(id);

    dispatch({
      type: DELETE_PARAM,
      payload: {
        id
      }
    });
  }
}

export function load() {
  return (dispatch, getState) => {
    dispatch({
      type: LOAD + _START
    });

    paramService.allParams(getState().common.productId).then(data => {
      dispatch({
        type: LOAD + _SUCCESS,
        payload: {
          items: data.collection
        }
      });

      data.collection.forEach(param => dispatch(loadParamItems(param.id)));
    }).catch(error => {
      dispatch({
        type: LOAD + _FAILURE,
        error
      });
    });
  };
}


export function saveParam(productId, paramId, name) {
  return dispatch => {
    dispatch({
      type: SAVE_PARAM + _START
    });

    paramService.save(productId, paramId, name).then(data => {
      dispatch({
        type: SAVE_PARAM + _SUCCESS,
        payload: {
          model: data.model
        }
      });
    }).catch(error => {
      dispatch({
        type: SAVE_PARAM + _FAILURE,
        error
      });
    });
  };
}

export function createParam(productId, name) {
  return dispatch => {
    dispatch({
      type: CREATE_PARAM + _START
    });

    paramService.create(productId, name).then(data => {
      dispatch({
        type: CREATE_PARAM + _SUCCESS,
        payload: {
          model: data.model
        }
      });
    }).catch(error => {
      dispatch({
        type: CREATE_PARAM + _FAILURE,
        error
      });
    });
  };
}


export function createParamItem(paramId) {
  return {
    type: CREATE_PARAM_ITEM,
    payload: {
      paramId
    }
  }
}

function syncParamItem(paramId, itemId) {
  return (dispatch, getState) => {
    const current = getState().params.paramItems[paramId].find(item => item.id === itemId);

    paramItemsService.save(current.serverId, paramId, current.model.name, current.model.description);
  }
}

export function updateParamItem(paramId, itemId, model) {
  return (dispatch, getState) => {

    const current = getState().params.paramItems[paramId].find(item => item.id === itemId);

    dispatch({
      type: UPDATE_PARAM_ITEM,
      payload: {
        paramId,
        itemId,
        model
      }
    });

    if (current.isNew) {

      if (current.loading === false) {
        dispatch({
          type: CREATE_PARAM_ITEM + _START,
          payload: {
            paramId: paramId,
            itemId: current.id
          }
        });

        paramItemsService.create(paramId, model.name, model.description).then(data => {
          dispatch({
            type: CREATE_PARAM_ITEM + _SUCCESS,
            payload: {
              paramId: paramId,
              oldId: current.id,
              model: data.model
            }
          });

          dispatch(syncParamItem(paramId, current.id));

        }).catch(error => {
          console.error(error);
          dispatch({
            type: CREATE_PARAM_ITEM + _FAILURE,
            payload: {
              paramId: paramId,
              itemId: current.id
            },
            error
          });
        });
      }
    } else {
      paramItemsService.save(current.serverId, paramId, model.name, model.description);
    }
  }
}

export function deleteParamItem(paramId, id) {
  return (dispatch, getState) => {

    const current = getState().params.paramItems[paramId].find(item => item.id === id);

    if (current.serverId !== null) {
      paramItemsService.deleteItem(current.serverId);
    }

    dispatch({
      type: DELETE_PARAM_ITEM,
      payload: { paramId, id }
    });
  }
}

export function moveParamItem(paramId, itemId, direction) {
  return dispatch => {
    dispatch({
      type: MOVE_PARAM_ITEM + _START,
      payload: {
        paramId,
        itemId,
        direction
      }
    });

    paramItemsService.moveItem(itemId, direction).then(data => {
      dispatch({
        type: MOVE_PARAM_ITEM + _SUCCESS,
        payload: {
          paramId,
          itemId,
          direction
        }
      });
    }).catch(err => {
      dispatch({
        type: MOVE_PARAM_ITEM + _FAILURE,
        payload: {
          itemId
        }
      });
    });
  };
}

export function loadParamItems(paramId) {
  return dispatch => {
    dispatch({
      type: GET_PARAM_ITEMS + _START,
      payload: {
        paramId
      }
    });

    paramItemsService.items(paramId).then(data => {
      dispatch({
        type: GET_PARAM_ITEMS + _SUCCESS,
        payload: {
          paramId,
          data: data.collection
        }
      });
    }).catch(error => {
      dispatch({
        type: GET_PARAM_ITEMS + _FAILURE,
        payload: {
          paramId
        },
        error
      });
    });
  };
}


export function savePrice(productId, firstParamId, secondParamId, value) {
  return dispatch => {
    dispatch({
      type: SAVE_PRICE + _START,
      payload: { firstParamId, secondParamId, value }
    });

    /*paramItemsService.price(productId, firstParamId, secondParamId, value).then(data => {
      dispatch({
        type: SAVE_PRICE + _SUCCESS,
        payload: { firstParamId, secondParamId }
      });
    }).catch(error => {
      dispatch({
        type: SAVE_PRICE + _FAILURE,
        payload: { firstParamId, secondParamId },
        error
      });
    });*/
  };
}

export function toggleDiscount(productId, firstParamId, secondParamId, enabled) {
  return dispatch => {
    dispatch({
      type: TOGGLE_DISCOUNT,
      payload: { firstParamId, secondParamId, enabled }
    });

    if (enabled) {
      //  dispatch(savePrice(productId, firstParamId, secondParamId, { discount_mode: 'FIXED' }))
    } else {
      // dispatch(savePrice(productId, firstParamId, secondParamId, { discount_mode: null, discount_value: null }))
    }
  };
}

export function loadPrices(productId) {
  return dispatch => {
    dispatch({
      type: GET_PRICES + _START
    });

    paramItemsService.prices(productId).then(data => {
      dispatch({
        type: GET_PRICES + _SUCCESS,
        payload: {
          items: data.collection
        }
      });
    }).catch(error => {
      dispatch({
        type: GET_PRICES + _FAILURE,
        error
      });
    });
  }
}

export function moveParam(paramId, direction) {
  return dispatch => {

    paramService.move(paramId, direction);

    dispatch({
      type: MOVE_PARAM,
      payload: {
        id: paramId,
        direction
      }
    });
  }
}