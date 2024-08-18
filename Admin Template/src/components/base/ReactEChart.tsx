import { Box, BoxProps } from '@mui/material';
import { EChartsReactProps } from 'echarts-for-react';
import EChartsReactCore from 'echarts-for-react/lib/core';
import ReactEChartsCore from 'echarts-for-react/lib/core';
import { forwardRef } from 'react';

export interface ReactEchartProps extends BoxProps {
  echarts: EChartsReactProps['echarts'];
  option: EChartsReactProps['option'];
}

const ReactEChart = forwardRef<null | EChartsReactCore, ReactEchartProps>(
  ({ option, ...rest }, ref) => {
    return (
      <Box
        component={ReactEChartsCore}
        ref={ref}
        option={{
          ...option,
          tooltip: {
            ...option.tooltip,
            confine: true,
          },
        }}
        {...rest}
      />
    );
  },
);

export default ReactEChart;
