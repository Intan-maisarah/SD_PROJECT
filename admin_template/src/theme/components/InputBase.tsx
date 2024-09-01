import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const InputBase: Components<Omit<Theme, 'components'>>['MuiInputBase'] = {
  defaultProps: {
    autoComplete: 'off',
  },
  styleOverrides: {
    root: ({ theme }) => ({
      borderRadius: theme.shape.borderRadius * 2,
      padding: 0,
    }),
    input: ({ theme }) => ({
      '&::placeholder': {
        color: theme.palette.action.focus,
      },
    }),
  },
};

export default InputBase;
