import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const PaginationItem: Components<Omit<Theme, 'components'>>['MuiPaginationItem'] = {
  defaultProps: {},
  styleOverrides: {
    previousNext: ({ theme }) => ({
      fontSize: theme.typography.body1.fontSize,
      fontWeight: theme.typography.body1.fontWeight,
      '.MuiTouchRipple-root': {
        color: theme.palette.primary.main,
      },
    }),
    page: ({ theme }) => ({
      borderRadius: theme.shape.borderRadius * 10,
      fontSize: theme.typography.subtitle1.fontSize,
      fontWeight: theme.typography.body1.fontWeight,
    }),
  },
};

export default PaginationItem;
