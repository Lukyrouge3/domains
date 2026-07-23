import { getUser } from '$lib/auth';
import { redirect } from '@sveltejs/kit';
import type { PageLoad } from './$types';

export const load: PageLoad = async () => {
	const user = await getUser();
	if (user) {
		redirect(302, '/dashboard');
	}
};
