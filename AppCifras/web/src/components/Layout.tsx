import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Navbar, NavbarBrand, NavbarCollapse, NavbarLink, NavbarToggle, Dropdown, DropdownItem } from 'flowbite-react';

const navLinks = [
  { to: '/', label: 'Início' },
  { to: '/artistas', label: 'Artistas' },
  { to: '/musicas', label: 'Músicas' },
  { to: '/playlists', label: 'Playlists' },
];

const navbarTheme = {
  root: {
    base: 'bg-space-900/95 border-b border-space-600/50 px-2 py-2.5 sm:px-4 backdrop-blur-sm',
    rounded: { on: 'rounded-none', off: '' },
    bordered: { on: 'border-b border-space-600/50', off: '' },
    inner: {
      base: 'mx-auto flex flex-wrap items-center justify-between w-full',
      fluid: { on: 'max-w-full px-4 sm:px-6 lg:px-8', off: '' },
    },
  },
  brand: { base: 'flex items-center' },
  collapse: {
    base: 'w-full md:block md:w-auto',
    list: 'mt-4 flex flex-col md:mt-0 md:flex-row md:space-x-1 md:gap-1 md:text-sm md:font-medium',
    hidden: { on: 'hidden', off: '' },
  },
  link: {
    base: 'block py-2 pl-3 pr-4 md:p-0 rounded-lg md:px-4 md:py-2 transition-colors',
    active: {
      on: 'bg-space-500 text-white',
      off: 'text-space-300 hover:bg-space-800 hover:text-space-100 border-0',
    },
    disabled: { on: 'opacity-50 cursor-not-allowed', off: '' },
  },
  toggle: {
    base: 'inline-flex items-center rounded-lg p-2 text-space-400 hover:bg-space-800 md:hidden',
    icon: 'h-6 w-6 shrink-0',
    title: 'sr-only',
  },
};

export default function Layout({ children }: { children: React.ReactNode }) {
  const { user, logout } = useAuth();
  const location = useLocation();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <div className="min-h-screen w-full flex flex-col bg-space-950 bg-space-gradient bg-nebula-gradient">
      <Navbar theme={navbarTheme} fluid className="flex-shrink-0">
        <NavbarBrand as={Link} {...({ to: '/' } as { to: string })} className="text-space-100 font-orbitron text-xl font-bold">
          App Cifras
        </NavbarBrand>
        <NavbarToggle />
        <NavbarCollapse>
          {navLinks.map(({ to, label }) => (
            <NavbarLink key={to} as={Link} {...({ to } as { to: string })} active={location.pathname === to || (to !== '/' && location.pathname.startsWith(to))}>
              {label}
            </NavbarLink>
          ))}
          <li className="mt-4 md:mt-0 md:ml-2 flex items-center">
            <Dropdown
              label={<span className="font-exo text-space-300 hover:text-space-100">{user?.email ?? 'Conta'}</span>}
              dismissOnClick
              theme={{
                inlineWrapper: 'flex items-center font-exo text-sm',
                content: 'py-1 bg-space-800 border border-space-600 min-w-[180px]',
                floating: {
                  item: {
                    base: 'flex items-center justify-start gap-2 px-4 py-2 text-sm text-space-200 hover:bg-space-700 hover:text-space-100 w-full',
                    icon: 'mr-2 h-4 w-4',
                  },
                },
              }}
            >
              <DropdownItem onClick={handleLogout} className="!text-red-600 hover:!bg-space-700 hover:!text-red-700">
                Sair
              </DropdownItem>
            </Dropdown>
          </li>
        </NavbarCollapse>
      </Navbar>

      <main className="flex-1 w-full px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div className="mx-auto w-full max-w-7xl">
          {children}
        </div>
      </main>
    </div>
  );
}
