import { ReactElement } from 'react';
import { Stack, Typography } from '@mui/material';
import Image from 'components/base/Image';
import noResultsSvg from 'assets/images/error/no-results.svg';

const CustomNoResultsOverlay = (): ReactElement => {
  return (
    <Stack height={1} px={6} justifyContent="center" alignItems="center" flexGrow={1}>
      <Image
        alt="no results overlay image"
        src={noResultsSvg}
        width={1}
        sx={{
          maxWidth: 'fit-content',
        }}
      />
      <Typography variant="h6" color="text.primary">
        No results found
      </Typography>
    </Stack>
  );
};

export default CustomNoResultsOverlay;
