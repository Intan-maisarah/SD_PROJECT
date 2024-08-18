import { Chip, LinearProgress, TableCell, TableRow } from '@mui/material';
import { ProductItem } from 'data/product-data';
import { ReactElement } from 'react';

const ProductItemRow = ({ productItem }: { productItem: ProductItem }): ReactElement => {
  return (
    <TableRow>
      <TableCell
        align="left"
        component="th"
        variant="head"
        scope="row"
        sx={{
          color: 'common.white',
          fontSize: 'body1.fontSize',
        }}
      >
        {productItem.id}
      </TableCell>
      <TableCell
        align="left"
        sx={{
          whiteSpace: 'nowrap',
        }}
      >
        {productItem.name}
      </TableCell>
      <TableCell align="left">
        <LinearProgress
          variant="determinate"
          color={productItem.color}
          value={productItem.sales}
          sx={{
            bgcolor: 'grey.900',
          }}
        />
      </TableCell>
      <TableCell align="center">
        <Chip
          label={`${productItem.sales}%`}
          color={productItem.color as any}
          variant="outlined"
          size="medium"
        />
      </TableCell>
    </TableRow>
  );
};

export default ProductItemRow;
