export const INTEGER = 'INTEGER';
export const SELECT = 'SELECT';
export const BOOLEAN = 'BOOLEAN';
export const STRING = 'STRING';

export function label(type) {
  switch (type) {
    case INTEGER:
      return 'Число';
    case SELECT:
      return 'Выбор';
    case BOOLEAN:
      return 'Да / Нет';
    case STRING:
      return 'Строка';
    default:
      return 'Неизвестный';
  }
}

export function values() {
  return [
    { id: STRING, label: label(STRING) },
    { id: INTEGER, label: label(INTEGER) },
    { id: BOOLEAN, label: label(BOOLEAN) },
    { id: SELECT, label: label(SELECT) },
  ];
}