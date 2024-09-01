export const currencyFormat = (
  amount: number | bigint,
  options: Intl.NumberFormatOptions = {},
): string => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'usd',
    maximumFractionDigits: 3,
    minimumFractionDigits: 0,
    useGrouping: true,
    notation: 'standard',
    ...options,
  }).format(amount);
};
