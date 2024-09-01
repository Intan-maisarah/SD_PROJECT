import { Theme, alpha } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const Chip: Components<Omit<Theme, 'components'>>['MuiChip'] = {
  defaultProps: {
    variant: 'outlined',
    size: 'small',
  },
  styleOverrides: {
    root: ({ theme }) => ({
      paddingTop: theme.spacing(1.25),
      paddingBottom: theme.spacing(1.25),
      borderRadius: theme.shape.borderRadius,
    }),
    sizeSmall: ({ theme }) => ({
      paddingRight: theme.spacing(2.5),
      paddingLeft: theme.spacing(2.5),
    }),
    sizeMedium: ({ theme }) => ({
      paddingRight: theme.spacing(4.25),
      paddingLeft: theme.spacing(4.25),
    }),
    label: ({ theme }) => ({
      paddingLeft: theme.spacing(2.5),
      paddingRight: theme.spacing(2.5),
      fontSize: theme.typography.body2.fontSize,
      fontWeight: theme.typography.body2.fontWeight,
    }),
    colorPrimary: ({ theme }) => ({
      color: theme.palette.primary.main,
      backgroundColor: alpha(theme.palette.primary.main, 0.12),
    }),
    colorSecondary: ({ theme }) => ({
      color: theme.palette.secondary.main,
      backgroundColor: alpha(theme.palette.secondary.main, 0.12),
    }),
    colorInfo: ({ theme }) => ({
      color: theme.palette.info.main,
      backgroundColor: alpha(theme.palette.info.main, 0.12),
    }),
    colorSuccess: ({ theme }) => ({
      color: theme.palette.success.main,
      backgroundColor: alpha(theme.palette.success.main, 0.12),
    }),
    colorWarning: ({ theme }) => ({
      color: theme.palette.warning.main,
      backgroundColor: alpha(theme.palette.warning.main, 0.12),
    }),
    colorError: ({ theme }) => ({
      color: theme.palette.error.main,
      backgroundColor: alpha(theme.palette.error.main, 0.12),
    }),
  },
};

export default Chip;
