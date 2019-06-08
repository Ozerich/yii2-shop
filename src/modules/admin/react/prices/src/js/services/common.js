import BaseService from './base';

export default class CommonService extends BaseService {
  init() {
    return this.query('/prices/init');
  }

  currencies() {
    return this.query('/prices/currencies');
  }

  saveCurrency(productId, currency) {
    return this.post('/prices/currency/' + productId, { currency: +currency });
  }

  products(request) {
    return this.post('/prices/products', request);
  }

  save(productId, paramValueId, secondParamValueId, data) {
    return this.post('/prices/save/' + productId, Object.assign({
      first_param_id: paramValueId,
      second_param_id: secondParamValueId,
    }, data));
  }

  saveModule(moduleId, price, discountMode, discountValue) {
    return this.post('/prices/save-module/' + moduleId, {
      price: price,
      discount_mode: discountMode,
      discount_value: discountValue
    });
  }
}