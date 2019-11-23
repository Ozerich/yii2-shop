import BaseService from './base';

export default class ParamsService extends BaseService {
  allParams(productId) {
    return this.query('/prices/params/' + productId);
  }

  deleteParam(paramId) {
    return this.delete('/prices/param/' + paramId);
  }

  save(productId, paramId, name) {
    return this.post('/prices/param/' + paramId, {
      product_id: productId,
      name
    });
  }

  move(paramId, direction) {
    return this.post('/prices/param-move/' + paramId + '?mode=' + (direction === -1 ? 'up' : 'down'));
  }

  create(productId, name) {
    return this.post('/prices/param', {
      product_id: productId,
      name
    });
  }

  saveBatch(productId, data) {
    return this.post('/prices/save-batch/' + productId, { prices: data });
  }
}