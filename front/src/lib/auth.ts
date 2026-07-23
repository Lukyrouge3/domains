import { PUBLIC_API_URL } from '$env/static/public';

export interface User {
	id: number;
	name: string;
	email: string;
	email_verified_at: string;
	created_at: string;
	updated_at: string;
}

export async function getUser(): Promise<User | null> {
	const res = await fetch(`${PUBLIC_API_URL}/api/user`, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			Accept: 'application/json'
		},
		credentials: 'include'
	});

	if (!res.ok) {
		return null;
	}

	const data = await res.json();
	return data as User;
}
