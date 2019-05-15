import BaseService from './base';

export default class ConditionsService extends BaseService {
  all(categoryId) {
    return this.query('/conditional/' + categoryId);
  }

  categories(categoryId) {
    return this.query('/conditional/' + categoryId + '/categories');
  }

  manufacturues(categoryId) {
    return this.query('/conditional/' + categoryId + '/manufactures');
  }

  save(categoryId, data) {
    return this.post('/conditional/' + categoryId + '/save', data);
  }
}

