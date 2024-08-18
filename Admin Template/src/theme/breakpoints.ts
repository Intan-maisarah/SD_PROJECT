import { BreakpointsOptions } from '@mui/material';

declare module '@mui/material/styles' {
  interface BreakpointOverrides {
    '2xl': true;
  }
}

const breakpoints: BreakpointsOptions = {
  values: {
    xs: 0,
    sm: 738,
    md: 960,
    lg: 1024,
    xl: 1280,
    '2xl': 1536,
  },
};

export default breakpoints;
