import { Theme, alpha } from '@mui/material';
import { Components } from '@mui/material/styles/components';
import pxToRem from 'theme/functions/px-to-rem';

const TableCell: Components<Omit<Theme, 'components'>>['MuiTableCell'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      paddingTop: theme.spacing(3.125),
      paddingBottom: theme.spacing(3.125),
      borderBottomWidth: pxToRem(0.5),
      borderBottomStyle: 'solid',
      borderBottomColor: alpha(theme.palette.common.white, 0.06),
    }),
    head: ({ theme }) => ({
      fontSize: theme.typography.subtitle1.fontSize,
      fontWeight: theme.typography.subtitle1.fontWeight,
      color: theme.palette.text.primary,
      lineHeight: '24.2px',
    }),
    body: ({ theme }) => ({
      fontSize: theme.typography.body1.fontSize,
      fontWeight: theme.typography.body1.fontWeight,
      color: theme.palette.common.white,
      lineHeight: '19.36px',
    }),
  },
};

export default TableCell;
