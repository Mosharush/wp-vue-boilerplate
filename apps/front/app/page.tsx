'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import UserForm from '@/components/UserForm';

export default function Entrance() {
  const router = useRouter();
  const [error, setError] = useState('');

  const handleSubmit = async (identifier: string, password: string) => {
    try {
      const res = await fetch(`${process.env.NEXT_PUBLIC_WP_API}/elementor-tracker/v1/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ identifier, password })
      });
      
      if (!res.ok) {
        const errorData = await res.json();
        throw new Error(errorData.message || 'Login failed');
      }
      
      const data = await res.json();
      localStorage.setItem('user', JSON.stringify(data.user));
      router.push('/dashboard');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Login failed. Please try again.');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50">
      <UserForm onSubmit={handleSubmit} error={error} />
    </div>
  );
}