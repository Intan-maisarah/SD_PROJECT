import { Components, Theme } from '@mui/material';

import InputAdornment from './components/InputAdornment';
import LinearProgress from './components/LinearProgress';
import ListItemButton from './components/ListItemButton';
import PaginationItem from './components/PaginationItem';
import TableContainer from './components/TableContainer';
import OutlinedInput from './components/OutlinedInput';
import DataGrid from './components/DataGrid/DataGrid';
import ListItemIcon from './components/ListItemIcon';
import ListItemText from './components/ListItemText';
import CardContent from './components/CardContent';
import CssBaseline from './components/CssBaseline';
import FilledInput from './components/FilledInput';
import ButtonBase from './components/ButtonBase';
import IconButton from './components/IconButton';
import InputLabel from './components/InputLabel';
import Pagination from './components/Pagination';
import CardMedia from './components/CardMedia';
import InputBase from './components/InputBase';
import TableCell from './components/TableCell';
import TextField from './components/TextField';
import Checkbox from './components/Checkbox';
import MenuItem from './components/MenuItem';
import Divider from './components/Divider';
import AppBar from './components/Appbar';
import Avatar from './components/Avatar';
import Button from './components/Button';
import Drawer from './components/Drawer';
import Grid2 from './components/Grid2';
import Paper from './components/Paper';
import Stack from './components/Stack';
import Card from './components/Card';
import Chip from './components/Chip';
import Grid from './components/Grid';
import Link from './components/Link';
import List from './components/List';
import Menu from './components/Menu';

const components: Components<Omit<Theme, 'components'>> = {
  MuiInputAdornment: InputAdornment,
  MuiLinearProgress: LinearProgress,
  MuiListItemButton: ListItemButton,
  MuiPaginationItem: PaginationItem,
  MuiTableContainer: TableContainer,
  MuiOutlinedInput: OutlinedInput,
  MuiListItemIcon: ListItemIcon,
  MuiListItemText: ListItemText,
  MuiCardContent: CardContent,
  MuiCssBaseline: CssBaseline,
  MuiFilledInput: FilledInput,
  MuiButtonBase: ButtonBase,
  MuiIconButton: IconButton,
  MuiInputLabel: InputLabel,
  MuiPagination: Pagination,
  MuiCardMedia: CardMedia,
  MuiInputBase: InputBase,
  MuiTableCell: TableCell,
  MuiTextField: TextField,
  MuiCheckbox: Checkbox,
  MuiDataGrid: DataGrid,
  MuiMenuItem: MenuItem,
  MuiDivider: Divider,
  MuiAppBar: AppBar,
  MuiAvatar: Avatar,
  MuiButton: Button,
  MuiDrawer: Drawer,
  MuiGrid2: Grid2,
  MuiPaper: Paper,
  MuiStack: Stack,
  MuiCard: Card,
  MuiChip: Chip,
  MuiGrid: Grid,
  MuiLink: Link,
  MuiList: List,
  MuiMenu: Menu,
};

export default components;
