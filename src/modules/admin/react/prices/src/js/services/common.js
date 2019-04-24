import BaseService from './base';

export default class CommonService extends BaseService {
  products() {
    return this.query('/prices/products');
  }

  save(productId, paramValueId, secondParamValueId, data) {
    return this.post('/prices/save/' + productId, Object.assign({
      first_param_id: paramValueId,
      second_param_id: secondParamValueId,
    }, data));
  }
}