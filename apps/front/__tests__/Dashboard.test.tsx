import { render, screen, act } from '@testing-library/react';
import Dashboard from '../app/dashboard/page';
import { renderWithProviders } from '../test-utils/test-utils';
import * as navigation from 'next/navigation';

// Mock next/navigation
jest.mock('next/navigation', () => ({
  useRouter: jest.fn(() => ({
    push: jest.fn(),
  })),
}));

describe('Dashboard', () => {
  let mockRouter: { push: jest.Mock };
  let fetchMock: jest.Mock;

  beforeEach(() => {
    jest.useFakeTimers();
    mockRouter = { push: jest.fn() };
    (navigation.useRouter as jest.Mock).mockReturnValue(mockRouter);
  });

  afterEach(() => {
    jest.useRealTimers();
    jest.clearAllMocks();
  });

  it('redirects to entrance if no user data', async () => {
    renderWithProviders(<Dashboard />);
    
    // Wait for all promises and timers
    await Promise.resolve();
    await jest.runAllTimersAsync();
    
    expect(mockRouter.push).toHaveBeenCalledWith('/');
  });
});