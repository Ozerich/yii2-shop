import BaseService from './base';

export default class GroupService extends BaseService {
  all(categoryId) {
    return this.query('/fields/' + categoryId + '/groups');
  }

  create(categoryId, name) {
    return this.post('/fields/' + categoryId + '/create-group', { name });
  }

  update(groupId, name) {
    return this.post('/fields/save-group/' + groupId, { name });
  }

  deleteGroup(groupId) {
    return this.delete('/fields/delete-group/' + groupId);
  }
}

