import BaseService from './base';

export default class ConditionsService extends BaseService {
  all(categoryId) {
    return this.query('/conditional/' + categoryId);
  }

  save(categoryId, data) {
    return this.post('/conditional/' + categoryId + '/save', data);
  }
}

