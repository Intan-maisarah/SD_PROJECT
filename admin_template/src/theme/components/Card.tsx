import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const Card: Components<Omit<Theme, 'components'>>['MuiCard'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({}) => ({
      display: 'flex',
      flexDirection: 'column',
    }),
  },
};

export default Card;
