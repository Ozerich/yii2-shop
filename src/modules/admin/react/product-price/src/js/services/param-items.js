import BaseService from './base';

export default class ParamItemsService extends BaseService {
  items(paramId) {
    return this.query('/prices/param-items/' + paramId);
  }

  deleteItem(itemId) {
    return this.delete('/prices/param-item/' + itemId);
  }

  save(itemId, paramId, name, description) {
    return this.post('/prices/param-item/' + itemId, {
      param_id: paramId,
      name,
      description
    });
  }

  moveItem(itemId, direction) {
    return this.post('/prices/move-param-item/' + itemId + '?mode=' + (direction === 1 ? 'down' : 'up'));
  }

  create(paramId, name, description) {
    return this.post('/prices/param-item', {
      param_id: paramId,
      name,
      description
    });
  }

  prices(productId) {
    return this.query('/prices/' + productId);
  }

  price(productId, firstParamId, secondParamId, value) {
    return this.post('/prices/save/' + productId, Object.assign({
      first_param_id: firstParamId,
      second_param_id: secondParamId,
    }, value));
  }
}