export const IN_SHOP = 'IN_SHOP';
export const STOCK = 'STOCK';
export const WAITING = 'WAITING';
export const NO = 'NO';

export function label(mode) {
  switch (mode) {
    case IN_SHOP:
      return 'В магазине';
    case STOCK:
      return 'На складе';
    case WAITING:
      return 'Под заказ';
    case NO:
      return 'Нет в наличии';
    default:
      return null;
  }
}

export function items() {
  return [
    { id: IN_SHOP, label: label(IN_SHOP) },
    { id: STOCK, label: label(STOCK) },
    { id: WAITING, label: label(WAITING) },
    { id: NO, label: label(NO) },
  ];
}