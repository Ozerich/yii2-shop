import BaseService from './base';

export default class CommonService extends BaseService {
  createModule(productId, name, sku, comment, price, discountMode, discountValue) {
    return this.post('/product-modules-api/' + productId + '/create', {
      name, sku, comment, price,
      discount_mode: discountMode,
      discount_value: discountValue
    })
  }

  list(productId) {
    return this.query('/product-modules-api/' + productId);
  }

  move(moduleId, direction) {
    return this.post('/product-modules-api/move/?id=' + moduleId + '&mode=' + direction);
  }

  remove(moduleId) {
    return this.post('/product-modules-api/delete/' + moduleId);
  }
}