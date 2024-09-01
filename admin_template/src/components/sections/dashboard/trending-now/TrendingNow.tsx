import { IconButton, Paper, Stack, Typography } from '@mui/material';
import IconifyIcon from 'components/base/IconifyIcon';
import ReactSwiper from 'components/base/ReactSwiper';
import { ReactElement, useMemo, useState } from 'react';
import { SwiperSlide } from 'swiper/react';
import { Swiper as SwiperClass } from 'swiper/types';
import SlideItem from './SlideItem';
import { trendingItems } from 'data/trending-items';

const TrendingNow = (): ReactElement => {
  const [, setSwiperRef] = useState<SwiperClass>();

  const trendingItemsSlides = useMemo(() => trendingItems, []);

  return (
    <Paper
      sx={{
        p: { xs: 4, sm: 8 },
        height: 1,
      }}
    >
      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
        mb={5}
        mr={-2}
        flexWrap="wrap"
      >
        <Typography variant="h4" color="common.white">
          Trending Now
        </Typography>
        <Stack direction="row" gap={1}>
          <IconButton
            className={`prev-arrow`}
            sx={{
              '&:disabled': {
                opacity: 0.5,
                cursor: 'default',
              },
              '&:hover': {
                bgcolor: 'transparent',
              },
            }}
            centerRipple
          >
            <IconifyIcon icon="mingcute:left-line" />
          </IconButton>
          <IconButton
            className={`next-arrow`}
            sx={{
              '&:disabled': {
                opacity: 0.5,
                cursor: 'default',
              },
              '&:hover': {
                bgcolor: 'transparent',
              },
            }}
            centerRipple
          >
            <IconifyIcon icon="mingcute:right-line" />
          </IconButton>
        </Stack>
      </Stack>
      <ReactSwiper
        onSwiper={setSwiperRef}
        sx={{
          height: 1,
        }}
      >
        {trendingItemsSlides.map((slideItem) => (
          <SwiperSlide key={slideItem.id}>
            <SlideItem trendingItem={slideItem} />
          </SwiperSlide>
        ))}
      </ReactSwiper>
    </Paper>
  );
};

export default TrendingNow;
