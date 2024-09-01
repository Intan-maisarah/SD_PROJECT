import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const FilledInput: Components<Omit<Theme, 'components'>>['MuiFilledInput'] = {
  defaultProps: {
    autoComplete: 'off',
  },
  styleOverrides: {
    root: ({ theme }) => ({
      borderRadius: theme.shape.borderRadius * 2,
      backgroundColor: theme.palette.background.paper,
      padding: 0,
      '&:hover, &.Mui-focused': {
        backgroundColor: theme.palette.grey[800],
      },
      '&::before, &::after': {
        borderBottom: 'none',
      },
      '&:hover:not(.Mui-disabled, .Mui-error):before': {
        borderBottom: 'none',
      },
      '&.Mui-disabled:before': {
        borderBottom: 'none',
      },
      '&:hover, &.Mui-disabled': {
        backgroundColor: theme.palette.divider,
      },
    }),
    input: ({ theme }) => ({
      padding: theme.spacing(3.625, 3),
      fontSize: theme.typography.body1.fontSize,
      fontWeight: theme.typography.body1.fontWeight,
      color: theme.palette.action.focus,
    }),
    disabled: ({ theme }) => ({
      backgroundColor: theme.palette.divider,
      '& :before': {
        borderBottom: 'none',
      },
    }),
    error: ({ theme }) => ({
      border: `1px solid ${theme.palette.error.main}`,
    }),
  },
};

export default FilledInput;
