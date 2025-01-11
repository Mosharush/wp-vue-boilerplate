'use client';

import { use } from 'react';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import UserModal from '@/components/UserModal';
import { User } from '@/types';

export default function UserModalPage({ params }: { params: Promise<{ id: string }> }) {
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const resolvedParams = use(params);

  useEffect(() => {
    const fetchUserDetails = async () => {
      try {
        const res = await fetch(`${process.env.NEXT_PUBLIC_WP_API}/elementor-tracker/v1/user/${resolvedParams.id}`);
        if (!res.ok) throw new Error('Failed to fetch user details');
        const data = await res.json();
        setUser(data);
      } catch (error) {
        console.error('Failed to fetch user details:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchUserDetails();
  }, [resolvedParams.id]);

  return user ? (
    <UserModal 
      user={user} 
      onClose={() => router.back()} 
      isLoading={loading}
    />
  ) : null;
}