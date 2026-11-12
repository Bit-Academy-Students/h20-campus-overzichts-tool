export type User = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    avatar?: string;
    role: 'municipality' | 'admin';
    created_at: string;
    [key: string]: unknown; // This allows for additional properties...
};

export type Auth = {
    user: User;
};
