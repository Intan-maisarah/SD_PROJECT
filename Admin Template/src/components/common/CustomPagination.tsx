import { Stack, Pagination, Typography, PaginationItem } from '@mui/material';
import {
  useGridSelector,
  gridPageSelector,
  useGridApiContext,
  gridPageCountSelector,
  gridExpandedRowCountSelector,
  gridPaginationRowRangeSelector,
} from '@mui/x-data-grid';
import { ReactElement } from 'react';

const CustomPagination = (): ReactElement => {
  const apiRef = useGridApiContext();
  const page = useGridSelector(apiRef, gridPageSelector);
  const pageCount = useGridSelector(apiRef, gridPageCountSelector);
  const available = useGridSelector(apiRef, gridExpandedRowCountSelector);
  const paginationRowRange = useGridSelector(apiRef, gridPaginationRowRangeSelector);

  return (
    <Stack
      width={1}
      spacing={2}
      direction={{ sm: 'row', xs: 'column' }}
      sx={{
        justifyContent: 'space-between',
        alignItems: 'center',
        mt: 2.5,
      }}
    >
      {available !== 0 ? (
        <>
          <Typography
            variant="body2"
            color="text.secondary"
            sx={{
              textWrap: 'wrap',
            }}
          >
            Showing {(paginationRowRange?.firstRowIndex as number) + 1}-
            {(paginationRowRange?.lastRowIndex as number) + 1} out of {available} data
          </Typography>
        </>
      ) : (
        <>
          <Typography
            variant="body2"
            color="text.secondary"
            sx={{
              textWrap: 'wrap',
            }}
          >
            Showing 0-0 out of {available} data
          </Typography>
        </>
      )}
      <Pagination
        shape="rounded"
        color="primary"
        count={pageCount}
        page={page + 1}
        onChange={(_event, value) => apiRef.current.setPage(value - 1)}
        renderItem={(item) => (
          <PaginationItem
            {...item}
            slots={{
              previous: () => <>Previous</>,
              next: () => <>Next</>,
            }}
          />
        )}
      />{' '}
    </Stack>
  );
};

export default CustomPagination;
