import { SvgIcon, SvgIconProps, useTheme } from '@mui/material';

const CheckBoxBlankIcon = (props: SvgIconProps) => {
  const theme = useTheme();
  return (
    <SvgIcon {...props} viewBox="0 0 13 13" fill="none">
      <rect
        x="1.16875"
        y="0.580859"
        width="11.6"
        height="11.6"
        rx="1.8"
        fill={theme.palette.text.primary}
        stroke={theme.palette.text.secondary}
        strokeWidth="0.4"
      />
    </SvgIcon>
  );
};

export default CheckBoxBlankIcon;
