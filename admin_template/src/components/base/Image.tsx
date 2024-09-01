import { Box, SxProps } from '@mui/material';
import { ImgHTMLAttributes } from 'react';

interface ImageProps extends ImgHTMLAttributes<HTMLImageElement> {
  src?: string;
  alt?: string;
  sx?: SxProps;
}

const Image = ({ src, alt, sx, ...rest }: ImageProps) => (
  <Box component="img" src={src} alt={alt} sx={sx} {...rest} />
);

export default Image;
