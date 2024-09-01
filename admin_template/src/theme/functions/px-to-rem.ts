const pxToRem = (number: number, baseNumber: number = 16) => {
  return `${number / baseNumber}rem`;
};

export default pxToRem;
