import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';

const OutlinedInput: Components<Omit<Theme, 'components'>>['MuiOutlinedInput'] = {
  defaultProps: { autoComplete: 'off' },
  styleOverrides: {
    root: ({ theme }) => ({
      paddingLeft: 0,
      borderRadius: theme.shape.borderRadius * 2.5,
      '&:hover .MuiOutlinedInput-notchedOutline': {
        borderColor: theme.palette.text.secondary,
        borderWidth: 1,
      },
      '&.Mui-focused .MuiOutlinedInput-notchedOutline': {
        borderColor: theme.palette.text.secondary,
        borderWidth: 1,
      },
      '&.MuiOutlinedInput-root .MuiOutlinedInput-notchedOutline > legend': {
        width: 0,
      },
    }),
    input: ({ theme }) => ({
      marginLeft: theme.spacing(3),
      color: theme.palette.text.secondary,
      '&::placeholder': {
        opacity: 1,
        color: theme.palette.text.primary,
      },
    }),
    notchedOutline: ({ theme }) => ({
      borderColor: theme.palette.text.primary,
      '&:hover': {
        borderColor: theme.palette.text.secondary,
      },
    }),
  },
};

export default OutlinedInput;
