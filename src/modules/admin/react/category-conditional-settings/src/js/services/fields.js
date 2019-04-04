import BaseService from './base';

export default class FieldService extends BaseService {
  parents(categoryId) {
    return this.query('/fields/' + categoryId + '/parents');
  }

  toggle(categoryId, fieldId, value) {
    return this.post('/fields/' + categoryId + '/toggle', { field_id: fieldId, value });
  }
}

