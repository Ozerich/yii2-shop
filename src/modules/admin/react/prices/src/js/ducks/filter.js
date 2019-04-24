import CommonService from '../services/common';

const service = new CommonService;

// Actions
const INIT = 'common/INIT';

const _START = '_START';
const _SUCCESS = '_SUCCESS';
const _FAILURE = '_FAILURE';

const initialState = {};

// Reducer
export default function reducer(state = initialState, action = {}) {
  switch (action.type) {


    default:
      return state;
  }
}
