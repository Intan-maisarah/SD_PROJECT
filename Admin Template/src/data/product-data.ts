import { LinearProgressProps } from '@mui/material';

export interface ProductItem {
  id?: string;
  name: string;
  color: LinearProgressProps['color'];
  sales: number;
}

export const productTableRows: ProductItem[] = [
  {
    id: '01',
    name: 'Home Decore Range',
    color: 'warning',
    sales: 78,
  },
  {
    id: '02',
    name: 'Disney Princess Dress',
    color: 'primary',
    sales: 62,
  },
  {
    id: '03',
    name: 'Bathroom Essentials',
    color: 'info',
    sales: 51,
  },
  {
    id: '04',
    name: 'Apple Smartwatch',
    color: 'secondary',
    sales: 29,
  },
];
