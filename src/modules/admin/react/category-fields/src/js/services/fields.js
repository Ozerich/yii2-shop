import BaseService from './base';

export default class FieldService extends BaseService {
  all(categoryId) {
    return this.query('/fields/' + categoryId);
  }
}

