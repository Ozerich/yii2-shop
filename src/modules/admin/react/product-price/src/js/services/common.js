import BaseService from './base';

export default class CommonService extends BaseService {
  enableExtendedMode(productId) {
    return this.post('/prices/toggle-extended/' + productId + '?value=1');
  }

  disableExtendedMode(productId) {
    return this.post('/prices/toggle-extended/' + productId + '?value=0');
  }

  save(productId, data) {
    return this.post('/prices/common-save/' + productId, data);
  }

  load(productId) {
    return this.query('/prices/load/' + productId);
  }

  currencies(){
    return this.query('/prices/currencies');
  }
}