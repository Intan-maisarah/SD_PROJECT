import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const MenuItem: Components<Omit<Theme, 'components'>>['MuiMenuItem'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      paddingLeft: theme.spacing(2.5),
      paddingRight: theme.spacing(2.5),
      borderRadius: theme.shape.borderRadius * 2.5,
      gap: 10,
      minWidth: 0,
      justifyContent: 'center',
      '+.MuiDivider-root': {
        marginTop: 0,
        marginBottom: 0,
      },
    }),
    divider: () => ({
      marginTop: 0,
      marginBottom: 0,
    }),
  },
};

export default MenuItem;
