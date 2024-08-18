import { ReactElement } from 'react';
import { Box, LinearProgress } from '@mui/material';

const Splash = (): ReactElement => {
  return (
    <Box sx={{ width: 1, height: '100vh' }}>
      <LinearProgress color="info" />
    </Box>
  );
};

export default Splash;
