import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const Divider: Components<Omit<Theme, 'components'>>['MuiDivider'] = {
  defaultProps: {},
  styleOverrides: {
    root: () => ({
      margin: 0,
    }),
  },
};

export default Divider;
