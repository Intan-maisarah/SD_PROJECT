import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const Avatar: Components<Omit<Theme, 'components'>>['MuiAvatar'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({ theme }) => ({
      alignItems: 'center',
      justifyContent: 'center',
      width: 28,
      height: 28,
      fontSize: theme.typography.body1.fontSize,
      fontWeight: theme.typography.body1.fontWeight,
    }),
  },
};

export default Avatar;
