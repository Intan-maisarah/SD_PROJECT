import homeDecorRange from 'assets/images/trending-now/home-decor-range.jpg';
import disneyPrincessDress from 'assets/images/trending-now/disney-princess-dresses.jpg';
import bathroomEssentials from 'assets/images/trending-now/bathroom-essentials.jpg';
import appleSmartwatch from 'assets/images/trending-now/apple-smartwatch.jpg';

export interface TrendingItem {
  id?: number;
  name: string;
  imgsrc: string;
  popularity: number;
  users: string[];
}

export const trendingItems: TrendingItem[] = [
  {
    id: 1,
    name: 'Home Decor Range',
    imgsrc: homeDecorRange,
    popularity: 78,
    users: ['Alex Xavier', 'Brian Edwards', 'George Oliver', 'Isaac Neville', 'Ulysses Parker'],
  },
  {
    id: 2,
    name: 'Disney Princess Dress',
    imgsrc: disneyPrincessDress,
    popularity: 62,
    users: ['David Olsen', 'Henry Irwin', 'Nathan Owens'],
  },
  {
    id: 3,
    name: 'Bathroom Essentials',
    imgsrc: bathroomEssentials,
    popularity: 51,
    users: [
      'William Edwards',
      'Alex Xavier',
      'Michael Evans',
      'Brian Edwards',
      'George Oliver',
      'Isaac Neville',
      'Nathan Owens',
    ],
  },
  {
    id: 4,
    name: 'Apple Smartwatch',
    imgsrc: appleSmartwatch,
    popularity: 29,
    users: ['Ulysses Parker', 'David Olsen'],
  },
];
