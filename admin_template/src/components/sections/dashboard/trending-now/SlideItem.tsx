import { ReactElement } from 'react';
import {
  Card,
  Stack,
  Avatar,
  CardMedia,
  Typography,
  AvatarGroup,
  CardContent,
  LinearProgress,
} from '@mui/material';
import { TrendingItem } from 'data/trending-items';
import { stringAvatar } from 'helpers/string-avatar';

const SlideItem = ({ trendingItem }: { trendingItem: TrendingItem }): ReactElement => {
  return (
    <Card
      sx={{
        bgcolor: 'background.default',
        height: 1,
      }}
    >
      <CardMedia
        image={trendingItem.imgsrc}
        sx={{
          height: 187,
        }}
      />
      <CardContent
        sx={{
          height: 110,
        }}
      >
        <Typography variant="body1" color="text.secondary" mb={2}>
          {trendingItem.name}
        </Typography>
        <Stack direction="row" justifyContent="space-between" color="text.primary" mb={2}>
          <Typography variant="body2">Popularity</Typography>
          <Typography variant="body2">{trendingItem.popularity}%</Typography>
        </Stack>
        <Stack gap={2}>
          <LinearProgress variant="determinate" color="info" value={trendingItem.popularity} />
          <AvatarGroup max={4}>
            {trendingItem.users.map((user, idx) => (
              <Avatar key={idx} {...stringAvatar(user)} />
            ))}
          </AvatarGroup>
        </Stack>
      </CardContent>
    </Card>
  );
};

export default SlideItem;
