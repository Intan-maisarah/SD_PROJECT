import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const List: Components<Omit<Theme, 'components'>>['MuiList'] = {
  defaultProps: {},
  styleOverrides: {
    root: () => ({
      display: 'flex',
      flexDirection: 'column',
      gap: 16,
    }),
  },
};

export default List;
