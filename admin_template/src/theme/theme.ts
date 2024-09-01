import { createTheme, ThemeOptions } from '@mui/material';

import components from './component-overrides';
import breakpoints from './breakpoints';
import typography from './typography';
import palette from './palette';
import spacing from './spacing';
import shape from './shape';

const theme: ThemeOptions = createTheme({
  breakpoints: breakpoints,
  components: components,
  typography: typography,
  palette: palette,
  spacing: spacing,
  shape: shape,
});

export default theme;
