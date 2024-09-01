import { Theme } from '@mui/material';

const swiper = (theme: Theme) => ({
  '& .swiper-button-prev, & .swiper-button-next': {
    position: 'relative !important',
    color: 'inherit !important',
    marginTop: '0px !important',
    '> span': {
      position: 'absolute',
      width: '26px',
      height: '26px',
      top: 'auto',
      right: 'auto',
      bottom: 'auto',
      left: 'auto',
      borderRadius: '9999px',
    },
  },
  '& .swiper-button-next:after, & .swiper-button-prev:after': {
    color: theme.palette.action.disabled,
    fontSize: `${theme.typography.h4.fontSize} !important`,
    fontWeight: theme.typography.h4.fontWeight,
  },
});

export default swiper;
