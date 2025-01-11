import { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Dashboard - User Tracking',
  description: 'User activity tracking dashboard',
};

export default function DashboardLayout({
  children,
  modal
}: {
  children: React.ReactNode;
  modal: React.ReactNode;
}) {
  return (
    <div className="min-h-screen bg-gray-50">
      {children}
      {modal}
    </div>
  );
} 