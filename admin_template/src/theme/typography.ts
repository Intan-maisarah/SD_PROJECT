import { TypographyOptions } from '@mui/material/styles/createTypography';
import pxToRem from './functions/px-to-rem';

const typography: TypographyOptions = {
  fontFamily: ['Inter', 'sans-serif'].join(', '),
  h1: {
    fontSize: pxToRem(48), // 3rem
    fontWeight: 700,
  },
  h2: {
    fontSize: pxToRem(40), // 3.5rem
    fontWeight: 700,
  },
  h3: {
    fontSize: pxToRem(32), // 2rem
    fontWeight: 600,
  },
  h4: {
    fontSize: pxToRem(24), // 1.5rem
    fontWeight: 600,
  },
  h5: {
    fontSize: pxToRem(24), // 1.5rem
    fontWeight: 500,
  },
  h6: {
    fontSize: pxToRem(20), // 1.25rem
    fontWeight: 600,
  },
  subtitle1: {
    fontSize: pxToRem(20), // 1.25rem
    fontWeight: 500,
  },
  subtitle2: {
    fontSize: pxToRem(16), // 1rem
    fontWeight: 600,
  },
  body1: {
    fontSize: pxToRem(16), // 1rem
    fontWeight: 500,
  },
  body2: {
    fontSize: pxToRem(12), // 0.75rem
    fontWeight: 500,
  },
  fontWeightLight: 400,
  fontWeightRegular: 500,
  fontWeightMedium: 600,
  fontWeightBold: 700,
};

export default typography;
