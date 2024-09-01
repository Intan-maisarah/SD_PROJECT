/* eslint-disable react-hooks/rules-of-hooks */
import {
  useState,
  useEffect,
  useContext,
  ReactElement,
  createContext,
  PropsWithChildren,
} from 'react';
import { Theme, Breakpoint } from '@mui/material';
import { useMediaQuery } from '@mui/material';

interface BreakpointContextInterface {
  currentBreakpoint: Breakpoint;
  up: (key: Breakpoint | number) => boolean;
  down: (key: Breakpoint | number) => boolean;
  only: (key: Breakpoint | number) => boolean;
  between: (start: Breakpoint | number, end: Breakpoint | number) => boolean;
}

export const BreakpointContext = createContext({} as BreakpointContextInterface);

const BreakpointsProvider = ({ children }: PropsWithChildren): ReactElement => {
  const [currentBreakpoint, setCurrentBreakpoint] = useState<Breakpoint>('xs');
  const up = (key: Breakpoint | number) =>
    useMediaQuery<Theme>((theme) => theme.breakpoints.up(key));

  const down = (key: Breakpoint | number) =>
    useMediaQuery<Theme>((theme) => theme.breakpoints.down(key));

  const only = (key: Breakpoint | number) =>
    useMediaQuery<Theme>((theme) => theme.breakpoints.only(key as Breakpoint));

  const between = (start: Breakpoint | number, end: Breakpoint | number) =>
    useMediaQuery<Theme>((theme) => theme.breakpoints.between(start, end));

  const isXs = between('xs', 'sm');
  const isSm = between('sm', 'md');
  const isMd = between('md', 'lg');
  const isLg = between('lg', 'xl');
  const isXl = between('xl', '2xl');
  const is2Xl = up('2xl');

  useEffect(() => {
    if (isXs) {
      setCurrentBreakpoint('xs');
    }
    if (isSm) {
      setCurrentBreakpoint('sm');
    }
    if (isMd) {
      setCurrentBreakpoint('md');
    }
    if (isLg) {
      setCurrentBreakpoint('lg');
    }
    if (isXl) {
      setCurrentBreakpoint('xl');
    }
    if (is2Xl) {
      setCurrentBreakpoint('2xl');
    }
  }, [isXs, isSm, isMd, isLg, isXl, is2Xl]);

  return (
    <BreakpointContext.Provider value={{ currentBreakpoint, up, down, only, between }}>
      {children}
    </BreakpointContext.Provider>
  );
};

export const useBreakpoints = () => useContext(BreakpointContext);

export default BreakpointsProvider;
