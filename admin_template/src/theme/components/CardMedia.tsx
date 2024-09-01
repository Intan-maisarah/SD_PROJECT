import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const CardMedia: Components<Omit<Theme, 'components'>>['MuiCardMedia'] = {
  defaultProps: {},
  styleOverrides: {
    root: ({}) => ({
      backgroundSize: 'cover',
      backgroundRepeat: 'no-repeat',
    }),
  },
};

export default CardMedia;
