import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const Drawer: Components<Omit<Theme, 'components'>>['MuiDrawer'] = {
  defaultProps: {},
  styleOverrides: {
    paper: ({ theme }) => ({
      borderRadius: 0,
      backgroundColor: theme.palette.background.default,
    }),
  },
};

export default Drawer;
