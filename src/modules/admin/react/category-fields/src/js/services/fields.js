import BaseService from './base';

export default class FieldService extends BaseService {
  all(categoryId) {
    return this.query('/fields/' + categoryId);
  }

  create(categoryId, model) {
    return this.post('/fields/' + categoryId + '/create-field', model);
  }

  update(fieldId, model) {
    return this.post('/fields/save-field/' + fieldId, model);
  }

  deleteField(fieldId) {
    return this.delete('/fields/delete-field/' + fieldId);
  }
}

