import { ReactElement } from 'react';
import { Link, Stack, Button, Typography } from '@mui/material';
import Image from 'components/base/Image';
import errorSvg from 'assets/images/error/error.svg';
import { rootPaths } from 'routes/paths';

const ErrorPage = (): ReactElement => {
  return (
    <Stack
      minHeight="100vh"
      width="fit-content"
      mx="auto"
      justifyContent="center"
      alignItems="center"
      gap={10}
      py={12}
    >
      <Typography variant="h1" color="text.secondary">
        Oops! Page Not Found!
      </Typography>
      <Typography
        variant="h5"
        fontWeight={400}
        color="text.primary"
        maxWidth={600}
        textAlign="center"
      >
        We couldn’t locate the page you’re trying to reach. We apologize for any inconvenience this
        may have caused. Thank you for your understanding!
      </Typography>
      <Image
        alt="Not Found Image"
        src={errorSvg}
        sx={{
          mx: 'auto',
          height: 260,
          my: { xs: 5, sm: 10 },
          width: { xs: 1, sm: 340 },
        }}
      />
      <Button href={rootPaths.homeRoot} size="large" variant="contained" component={Link}>
        Go to Home
      </Button>
    </Stack>
  );
};

export default ErrorPage;
