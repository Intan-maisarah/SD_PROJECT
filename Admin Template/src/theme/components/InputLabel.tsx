import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const InputLabel: Components<Omit<Theme, 'components'>>['MuiInputLabel'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      position: 'static',
      transform: 'none',
      fontSize: theme.typography.body1.fontSize,
      fontWeight: theme.typography.body1.fontWeight,
      '&.Mui-focused': {
        color: theme.palette.common.white,
      },
    }),
  },
};

export default InputLabel;
