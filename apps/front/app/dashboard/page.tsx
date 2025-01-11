'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import UserList from '@/components/UserList';
import { User } from '@/types';

export default function Dashboard() {
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [users, setUsers] = useState<User[]>([]);

  useEffect(() => {
    const userData = localStorage.getItem('user');
    if (!userData) {
      router.push('/');
      return;
    }
    const parsedUser = JSON.parse(userData);
    setUser(parsedUser);

    const fetchUsers = async () => {
      try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_WP_API}/elementor-tracker/v1/users`, {
          headers: {
            'X-WP-Nonce': parsedUser.nonce
          }
        });
        if (!res.ok) throw new Error('Failed to fetch users');
        const data = await res.json();
        setUsers(data);
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    };

    const sendHeartbeat = async () => {
      try {
        await fetch(`${process.env.NEXT_PUBLIC_WP_API}/elementor-tracker/v1/user/${parsedUser.id}/activity`, {
          method: 'POST',
          headers: {
            'X-WP-Nonce': parsedUser.nonce
          }
        });
      } catch (error) {
        console.error('Error sending heartbeat:', error);
      }
    };

    // Initial calls
    fetchUsers();
    sendHeartbeat();

    // Set up intervals
    const heartbeatInterval = setInterval(sendHeartbeat, 3000);
    const fetchUsersInterval = setInterval(fetchUsers, 3000);

    return () => {
      clearInterval(heartbeatInterval);
      clearInterval(fetchUsersInterval);
    };
  }, [router]);

  useEffect(() => {
    const handleBeforeUnload = () => {
      if (user?.id) {
        fetch(`${process.env.NEXT_PUBLIC_WP_API}/elementor-tracker/v1/user/${user.id}/offline`, {
          method: 'POST',
          headers: {
            'X-WP-Nonce': user.nonce
          },
          keepalive: true
        });
      }
    };

    window.addEventListener('beforeunload', handleBeforeUnload);
    return () => window.removeEventListener('beforeunload', handleBeforeUnload);
  }, [user]);

  const handleLogout = () => {
    if (user?.id) {
      fetch(`${process.env.NEXT_PUBLIC_WP_API}/elementor-tracker/v1/user/${user.id}/offline`, {
        method: 'POST',
        headers: {
          'X-WP-Nonce': user.nonce
        }
      });
    }
    localStorage.removeItem('user');
    router.push('/');
  };

  if (!user) return null;

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="space-y-8">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p className="text-gray-600">Welcome back, {user.name}!</p>
          </div>
          <button
            onClick={handleLogout}
            className="btn btn-danger"
          >
            Logout
          </button>
        </div>
        
        <div className="card">
          <h2 className="text-xl font-semibold mb-4">Active Users</h2>
          <UserList users={users} />
        </div>
      </div>
    </div>
  );
}