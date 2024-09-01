import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const TextField: Components<Omit<Theme, 'components'>>['MuiTextField'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      gap: theme.spacing(1),
    }),
  },
};

export default TextField;
