import BaseService from './base';

export default class CommonService extends BaseService {
  tree() {
    return this.query('/categories-api/tree');
  }

  fields(categoryId) {
    return this.query('/fields/' + categoryId + '?per-page=10000');
  }

  submit(categoryId, fieldIds) {
    return this.post('/products-api/product-params', {
      category_id: +categoryId,
      fields: fieldIds
    });
  }

  update(productId, fieldId, value) {
    return this.post('/products-api/update-param', {
      product_id: +productId,
      field_id: +fieldId,
      value
    })
  }
}