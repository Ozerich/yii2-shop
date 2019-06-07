export const DISCOUNT_MODE_PERCENT = 'PERCENT';
export const DISCOUNT_MODE_MONEY = 'AMOUNT';
export const DISCOUNT_MODE_VALUE = 'FIXED';

export function label(value) {
  switch (value) {
    case DISCOUNT_MODE_PERCENT:
      return 'Скидка в %';
    case DISCOUNT_MODE_MONEY:
      return 'Скидка на сумму';
    case DISCOUNT_MODE_VALUE:
      return 'Цена со скидкой';
    default:
      return null;
  }
}

export function list() {
  return {
    [DISCOUNT_MODE_PERCENT]: label(DISCOUNT_MODE_PERCENT),
    [DISCOUNT_MODE_MONEY]: label(DISCOUNT_MODE_MONEY),
    [DISCOUNT_MODE_VALUE]: label(DISCOUNT_MODE_VALUE),
  };
}