import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const LinearProgress: Components<Omit<Theme, 'components'>>['MuiLinearProgress'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      borderRadius: theme.shape.borderRadius * 5,
      backgroundColor: theme.palette.common.white,
    }),
    bar: ({ theme }) => ({
      borderRadius: theme.shape.borderRadius * 5,
    }),
  },
};

export default LinearProgress;
