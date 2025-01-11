'use client';

import { useRouter } from 'next/navigation';
import { User } from '@/types';

interface UserListProps {
  users: User[];
}

export default function UserList({ users }: UserListProps) {
  const router = useRouter();

  const handleUserClick = (userId: number) => {
    router.push(`/dashboard/user/${userId}`);
  };

  if (users.length === 0) {
    return (
      <div data-testid="empty-state" className="text-center py-8">
        <p className="text-gray-500">No users found</p>
      </div>
    );
  }

  return (
    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      {users.map((user) => (
        <div
          key={user.id}
          onClick={() => handleUserClick(user.id)}
          className="card cursor-pointer hover:shadow-lg transition-shadow"
        >
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold">{user.name}</h3>
            <span className={`px-2 py-1 text-sm rounded-full ${
              user.status === 'online' 
                ? 'bg-green-100 text-green-800' 
                : 'bg-gray-100 text-gray-800'
            }`}>
              {user.status}
            </span>
          </div>
          <div className="space-y-2 text-gray-600">
            <p className="text-sm">IP: {user.ip_address}</p>
            <p className="text-sm">Visits: {user.visits_count}</p>
            <p className="text-sm">Last seen: {new Date(Number(user.last_update) * 1000).toLocaleString()}</p>
            <p className="text-sm">Entrance time: {new Date(Number(user.entrance_time) * 1000).toLocaleString()}</p>
          </div>
        </div>
      ))}
    </div>
  );
}