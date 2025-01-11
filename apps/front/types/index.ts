export interface User {
    id: number;
    name: string;
    email: string;
    username: string;
    status: 'online' | 'offline';
    entrance_time: string;
    last_update: string;
    ip_address: string;
    user_agent: string;
    visits_count: number;
    nonce: string;
}

export interface UserResponse {
    token: string;
    user: User;
}
