import React from 'react';
import { render } from '@testing-library/react';

// Create a wrapper with necessary providers
export function renderWithProviders(ui: React.ReactElement) {
  return render(ui);
}

// Mock router functions that can be used in tests
export const mockRouter = {
  push: jest.fn(),
  replace: jest.fn(),
  back: jest.fn(),
  forward: jest.fn(),
}; 