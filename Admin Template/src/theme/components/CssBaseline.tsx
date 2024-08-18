import { Theme } from '@mui/material';
import { Components } from '@mui/material/styles/components';
import scrollbar from 'theme/styles/scrollbar';
import simplebar from 'theme/styles/simplebar';
import echart from 'theme/styles/echart';
import swiper from 'theme/styles/swiper';

const CssBaseline: Components<Omit<Theme, 'components'>>['MuiCssBaseline'] = {
  defaultProps: {},
  styleOverrides: (theme) => ({
    html: {
      scrollBehavior: 'smooth',
      WebkitOverflowScrolling: 'touch',
      boxSizing: 'border-box',
      scrollbarColor: '#21222D #171821',
    },
    '*, *::before, *::after': {
      margin: 0,
      padding: 0,
    },
    'a, a:link, a:visited': {
      textDecoration: 'none !important',
    },
    'a.link, .link, a.link:link, .link:link, a.link:visited, .link:visited': {
      color: `${theme.palette.primary.main} !important`,
      transition: 'color 150ms ease-in !important',
    },
    'a.link:hover, .link:hover, a.link:focus, .link:focus': {
      color: `${theme.palette.info.main} !important`,
    },
    body: {
      fontVariantLigatures: 'none',
      ...scrollbar(theme),
    },
    ...simplebar(theme),
    ...swiper(theme),
    ...echart(),
  }),
};

export default CssBaseline;
