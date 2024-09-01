import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const TableContainer: Components<Omit<Theme, 'components'>>['MuiTableContainer'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({}) => ({
      overflowX: 'auto',
      scrollbarWidth: 'thin',
    }),
  },
};

export default TableContainer;
