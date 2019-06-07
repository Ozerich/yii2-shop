export const FIXED = 'FIXED';
export const AMOUNT = 'AMOUNT';
export const PERCENT = 'PERCENT';

export function label(mode) {
  switch (mode) {
    case FIXED:
      return 'Цена со скидкой';
    case AMOUNT:
      return 'Скидка на сумму';
    case PERCENT:
      return 'Скидка в процентах';
    default:
      return null;
  }
}

export function items() {
  return [
    { id: FIXED, label: label(FIXED) },
    { id: AMOUNT, label: label(AMOUNT) },
    { id: PERCENT, label: label(PERCENT) },
  ];
}