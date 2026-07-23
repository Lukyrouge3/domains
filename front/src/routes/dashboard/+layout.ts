import { getUser } from '$lib/auth';
import { redirect } from '@sveltejs/kit';
import type { LayoutLoad } from './$types';

export const load: LayoutLoad = async () => {
	const user = await getUser();
	if (!user) {
		redirect(302, '/');
	}

	return { user };
};
