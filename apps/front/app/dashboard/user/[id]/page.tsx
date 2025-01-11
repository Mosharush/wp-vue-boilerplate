import { redirect } from 'next/navigation';

export default function UserPage({ params }: { params: { id: string } }) {
  redirect('/dashboard');
} 