import BaseService from './base';

export default class GroupService extends BaseService {
  all(categoryId) {
    return this.query('/fields/' + categoryId + '/groups');
  }
}

