// components/UserModal.tsx
import React from 'react';
import { User } from '@/types';

interface UserModalProps {
  user: User;
  onClose: () => void;
  isLoading?: boolean;
}

export default function UserModal({ user, onClose, isLoading = false }: UserModalProps) {
  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div className="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div className="p-6">
          {isLoading ? (
            <div className="flex justify-center items-center h-40">
              <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
            </div>
          ) : (
            <>
              <div className="flex justify-between items-start mb-4">
                <h2 className="text-2xl font-bold text-gray-900">{user.name}</h2>
                <button
                  onClick={onClose}
                  className="text-gray-400 hover:text-gray-500 transition-colors"
                >
                  <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <div className="space-y-4">
                <div>
                  <label className="text-sm font-medium text-gray-500">Email</label>
                  <p className="mt-1">{user.email}</p>
                </div>

                <div>
                  <label className="text-sm font-medium text-gray-500">IP Address</label>
                  <p className="mt-1">{user.ip_address}</p>
                </div>

                <div>
                  <label className="text-sm font-medium text-gray-500">User Agent</label>
                  <p className="mt-1 text-sm break-words">{user.user_agent}</p>
                </div>

                <div>
                  <label className="text-sm font-medium text-gray-500">Visit Count</label>
                  <p className="mt-1">{user.visits_count}</p>
                </div>

                <div>
                  <label className="text-sm font-medium text-gray-500">First Seen</label>
                  <p className="mt-1">
                    {new Date(Number(user.entrance_time) * 1000).toLocaleString()}
                  </p>
                </div>

                <div>
                  <label className="text-sm font-medium text-gray-500">Last Update</label>
                  <p className="mt-1">
                    {new Date(Number(user.last_update) * 1000).toLocaleString()}
                  </p>
                </div>
              </div>
            </>
          )}
        </div>
        
        <div className="bg-gray-50 px-6 py-4 rounded-b-lg">
          <button
            onClick={onClose}
            className="btn btn-primary w-full"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  );
}