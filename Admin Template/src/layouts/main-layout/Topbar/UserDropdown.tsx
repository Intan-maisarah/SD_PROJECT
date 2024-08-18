import { Menu, Avatar, Button, Tooltip, MenuItem, ListItemIcon, ListItemText } from '@mui/material';
import IconifyIcon from 'components/base/IconifyIcon';
import profile from 'assets/images/account/Profile.png';
import { useState, MouseEvent, useCallback, ReactElement } from 'react';
import userMenuItems from 'data/usermenu-items';

const UserDropdown = (): ReactElement => {
  const [anchorEl, setAnchorEl] = useState<null | HTMLElement>(null);
  const menuOpen = Boolean(anchorEl);

  const handleUserClick = useCallback((event: MouseEvent<HTMLElement>) => {
    setAnchorEl(event.currentTarget);
  }, []);

  const handleUserClose = useCallback(() => {
    setAnchorEl(null);
  }, []);

  return (
    <>
      <Button
        color="inherit"
        variant="text"
        id="account-dropdown-menu"
        aria-controls={menuOpen ? 'account-dropdown-menu' : undefined}
        aria-haspopup="true"
        aria-expanded={menuOpen ? 'true' : undefined}
        onClick={handleUserClick}
        disableRipple
        sx={{
          borderRadius: 2,
          gap: 3.75,
          px: { xs: 0, sm: 0.625 },
          py: 0.625,
          '&:hover': {
            bgcolor: 'transparent',
          },
        }}
      >
        <Tooltip title="Nickelfox" arrow placement="bottom">
          <Avatar src={profile} sx={{ width: 44, height: 44 }} />
        </Tooltip>
        <IconifyIcon
          color="common.white"
          icon="mingcute:down-fill"
          width={22.5}
          height={22.5}
          sx={(theme) => ({
            transform: menuOpen ? `rotate(180deg)` : `rotate(0deg)`,
            transition: theme.transitions.create('all', {
              easing: theme.transitions.easing.sharp,
              duration: theme.transitions.duration.short,
            }),
          })}
        />
      </Button>
      <Menu
        id="account-dropdown-menu"
        anchorEl={anchorEl}
        open={menuOpen}
        onClose={handleUserClose}
        MenuListProps={{
          'aria-labelledby': 'account-dropdown-button',
        }}
        transformOrigin={{ horizontal: 'right', vertical: 'top' }}
        anchorOrigin={{ horizontal: 'right', vertical: 'bottom' }}
      >
        {userMenuItems.map((userMenuItem) => (
          <MenuItem key={userMenuItem.id} onClick={handleUserClose}>
            <ListItemIcon
              sx={{
                minWidth: `0 !important`,
                color: userMenuItem.color,
                width: 14,
                height: 10,
                mb: 1.5,
              }}
            >
              <IconifyIcon icon={userMenuItem.icon} color={userMenuItem.color} />
            </ListItemIcon>
            <ListItemText
              sx={(theme) => ({
                color: userMenuItem.color,
                '& .MuiListItemText-primary': {
                  fontSize: theme.typography.subtitle2.fontSize,
                  fontFamily: theme.typography.subtitle2.fontFamily,
                  fontWeight: theme.typography.subtitle2.fontWeight,
                },
              })}
            >
              {userMenuItem.title}
            </ListItemText>
          </MenuItem>
        ))}
      </Menu>
    </>
  );
};

export default UserDropdown;
