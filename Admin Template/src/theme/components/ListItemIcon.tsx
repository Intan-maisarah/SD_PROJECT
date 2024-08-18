import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const ListItemIcon: Components<Omit<Theme, 'components'>>['MuiListItemIcon'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      minWidth: 0,
      width: theme.spacing(3.5),
      height: theme.spacing(3.5),
      color: theme.palette.background.default,
      justifyContent: 'center',
    }),
  },
};

export default ListItemIcon;
