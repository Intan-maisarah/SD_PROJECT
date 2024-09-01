import totalSales from 'assets/images/todays-sales/total-sales.png';
import totalOrder from 'assets/images/todays-sales/total-order.png';
import productSold from 'assets/images/todays-sales/product-sold.png';
import newCustomer from 'assets/images/todays-sales/new-customer.png';

export interface SaleItem {
  id?: number;
  icon: string;
  title: string;
  subtitle: string;
  increment: number;
  color: string;
}

const salesData: SaleItem[] = [
  {
    id: 1,
    icon: totalSales,
    title: '$5k',
    subtitle: 'Total Sales',
    increment: 10,
    color: 'warning.main',
  },
  {
    id: 2,
    icon: totalOrder,
    title: '500',
    subtitle: 'Total Order',
    increment: 8,
    color: 'primary.main',
  },
  {
    id: 3,
    icon: productSold,
    title: '9',
    subtitle: 'Product Sold',
    increment: 2,
    color: 'secondary.main',
  },
  {
    id: 4,
    icon: newCustomer,
    title: '12',
    subtitle: 'New Customer',
    increment: 3,
    color: 'info.main',
  },
];

export default salesData;
