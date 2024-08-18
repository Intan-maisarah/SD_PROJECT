import { ReactElement, PropsWithChildren } from 'react';
import { Box, Stack } from '@mui/material';

const AuthLayout = ({ children }: PropsWithChildren): ReactElement => {
  return (
    <>
      <Stack minHeight="100vh" justifyContent="center" py={10}>
        <Box maxWidth={640} width={1} mx="auto" px={5}>
          {children}
        </Box>
      </Stack>
    </>
  );
};

export default AuthLayout;
