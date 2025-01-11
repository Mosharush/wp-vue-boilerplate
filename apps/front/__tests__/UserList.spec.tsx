import { render, screen, fireEvent, act } from '@testing-library/react';
import UserList from '../components/UserList';
import { renderWithProviders } from '../test-utils/test-utils';
import * as navigation from 'next/navigation';
import { User } from '../types/index';

// Mock next/navigation
jest.mock('next/navigation', () => ({
  useRouter: jest.fn(() => ({
    push: jest.fn(),
  })),
}));

const mockUsers: User[] = [
  {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    username: 'johndoe',
    status: 'online',
    entrance_time: new Date().toISOString(),
    last_update: new Date().toISOString(),
    ip_address: '127.0.0.1',
    user_agent: 'Mozilla/5.0',
    visits_count: 1
  }
];

describe('UserList', () => {
  let mockRouter: { push: jest.Mock };

  beforeEach(() => {
    mockRouter = { push: jest.fn() };
    (navigation.useRouter as jest.Mock).mockReturnValue(mockRouter);
  });

  it('renders user list', async () => {
    renderWithProviders(<UserList users={mockUsers} />);
    
    const userElement = screen.getByText('John Doe');
    expect(userElement).toBeInTheDocument();
  });

  it('calls onUserClick when user is clicked', async () => {
    await act(async () => {
      renderWithProviders(<UserList users={mockUsers} />);
    });
    
    const userElement = screen.getByText('John Doe');
    
    await act(async () => {
      fireEvent.click(userElement);
    });

    expect(mockRouter.push).toHaveBeenCalledWith(`/dashboard/user/${mockUsers[0].id}`);
  });

  it('displays empty state when no users', async () => {
    await act(async () => {
      renderWithProviders(<UserList users={[]} />);
    });
    
    const emptyState = screen.getByTestId('empty-state');
    expect(emptyState).toBeInTheDocument();
  });
});