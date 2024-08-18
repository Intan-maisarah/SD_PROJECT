import { ReactElement } from 'react';
import { Box, Stack, useTheme, StackOwnProps, CircularProgress } from '@mui/material';

const PageLoader = (props: StackOwnProps): ReactElement => {
  const theme = useTheme();
  return (
    <Stack width={1} height={1} justifyContent="center" alignItems="center" {...props}>
      <Box height="10vh" width="25vw" textAlign="center">
        <svg width={0} height={0}>
          <defs>
            <linearGradient id="page_loader_gradient" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stopColor={theme.palette.primary.main} />
              <stop offset="33%" stopColor={theme.palette.secondary.main} />
              <stop offset="67%" stopColor={theme.palette.info.main} />
              <stop offset="100%" stopColor={theme.palette.warning.main} />
            </linearGradient>
          </defs>
        </svg>
        <CircularProgress
          size={150}
          thickness={5}
          sx={{ 'svg circle': { stroke: `url(#page_loader_gradient)` } }}
        />
      </Box>
    </Stack>
  );
};

export default PageLoader;
