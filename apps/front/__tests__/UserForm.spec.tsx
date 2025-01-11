import { render, screen, fireEvent, act } from '@testing-library/react';
import UserForm from '../components/UserForm';
import userEvent from '@testing-library/user-event';

describe('UserForm', () => {
  const mockSubmit = jest.fn();

  beforeEach(() => {
    mockSubmit.mockClear();
  });

  it('renders form fields', () => {
    render(<UserForm onSubmit={mockSubmit} />);
    
    const identifierInput = screen.getByLabelText(/email or username/i);
    const passwordInput = screen.getByLabelText(/password/i);

    expect(identifierInput).toBeInTheDocument();
    expect(passwordInput).toBeInTheDocument();
  });

  it('submits form with user data', async () => {
    render(<UserForm onSubmit={mockSubmit} />);
    
    const identifierInput = screen.getByLabelText(/email or username/i);
    const passwordInput = screen.getByLabelText(/password/i);
    const submitButton = screen.getByRole('button', { name: /sign in/i });
    
    await act(async () => {
      await userEvent.type(identifierInput, 'john@example.com');
      await userEvent.type(passwordInput, 'password123');
      fireEvent.click(submitButton);
    });

    expect(mockSubmit).toHaveBeenCalledWith('john@example.com', 'password123');
  });
});