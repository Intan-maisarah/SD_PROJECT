import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const Menu: Components<Omit<Theme, 'components'>>['MuiMenu'] = {
  defaultProps: {
    disableScrollLock: true,
  },
  styleOverrides: {
    list: ({ theme }) => ({
      gap: 4,
      padding: theme.spacing(1),
    }),
  },
};

export default Menu;
