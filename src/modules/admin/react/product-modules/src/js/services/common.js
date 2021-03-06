import BaseService from './base';

export default class CommonService extends BaseService {
  createModule(productId, name, sku, comment, price, discountMode, discountValue, images, params) {
    return this.post('/product-modules-api/' + productId + '/create', {
      name, sku, comment, price,
      discount_mode: discountMode,
      discount_value: discountValue,
      images, params
    })
  }

  createModuleFromCatalog(productId, moduleProductId) {
    return this.post('/product-modules-api/' + productId + '/create-catalog', {
      product_id: moduleProductId
    });
  }

  list(productId) {
    return this.query('/product-modules-api/' + productId);
  }

  move(moduleId, direction) {
    return this.post('/product-modules-api/move/?id=' + moduleId + '&mode=' + direction);
  }

  quantity(moduleId, value) {
    return this.post('/product-modules-api/quantity/' + moduleId, { value });
  }

  remove(moduleId) {
    return this.post('/product-modules-api/delete/' + moduleId);
  }

  upload(file) {
    return this.multipart('/product-modules-api/upload/?scenario=product', { file: file });
  }

  search(query) {
    return this.query('/products-api/search?query=' + query);
  }
}