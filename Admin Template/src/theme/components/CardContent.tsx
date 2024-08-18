import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const CardContent: Components<Omit<Theme, 'components'>>['MuiCardContent'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      height: theme.spacing(25),
      padding: theme.spacing(3.5),
      display: 'flex',
      flexDirection: 'column',
      justifyContent: 'space-around',
      flexGrow: 1,
      '&:last-child': {
        paddingBottom: theme.spacing(3.5),
      },
    }),
  },
};

export default CardContent;
