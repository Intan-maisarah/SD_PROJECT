import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const ListItemButton: Components<Omit<Theme, 'components'>>['MuiListItemButton'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      borderRadius: theme.shape.borderRadius * 1.5,
      padding: theme.spacing(2.5, 4),
      justifyContent: 'center',
      alignItems: 'center',
      gap: 5,
    }),
  },
};

export default ListItemButton;
