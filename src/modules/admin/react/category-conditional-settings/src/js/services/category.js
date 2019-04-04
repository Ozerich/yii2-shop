import BaseService from './base';

export default class CategoryService extends BaseService {
  get(categoryId) {
    return this.query('/fields/' + categoryId + '/category');
  }
}
