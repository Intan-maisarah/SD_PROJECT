import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const Button: Components<Omit<Theme, 'components'>>['MuiButton'] = {
  defaultProps: {
    variant: 'contained',
    size: 'medium',
  },
  styleOverrides: {
    root: ({ theme }) => ({
      borderRadius: theme.shape.borderRadius * 1.5,
      textTransform: 'none',
    }),
    sizeSmall: ({ theme }) => ({
      padding: theme.spacing(1.25, 2),
      fontSize: theme.typography.body1.fontSize,
      fontWeight: theme.typography.body1.fontWeight,
    }),
    sizeMedium: ({ theme }) => ({
      padding: theme.spacing(2.5, 4),
      fontSize: theme.typography.subtitle1.fontSize,
      fontWeight: theme.typography.subtitle2.fontWeight,
    }),
    sizeLarge: ({ theme }) => ({
      padding: theme.spacing(2.5, 6),
      fontSize: theme.typography.h5.fontSize,
      fontWeight: theme.typography.h5.fontWeight,
    }),
    disabled: ({ theme }) => ({
      backgroundColor: theme.palette.action.disabled,
    }),
  },
};

export default Button;
