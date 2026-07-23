<script lang="ts">
	import Button from '$lib/components/button.svelte';
	import * as Card from '$lib/components/ui/card/index.js';
	import { Input } from '$lib/components/ui/input/index.js';
	import {
		FieldGroup,
		Field,
		FieldLabel,
		FieldDescription
	} from '$lib/components/ui/field/index.js';
	import { goto } from '$app/navigation';
	import { resolve } from '$app/paths';

	const id = $props.id();

	let loading = $state(false);

	let password = $state('');
	let email = $state('');

	async function login(e: SubmitEvent) {
		e.preventDefault();
		loading = true;
		// Get the csrf-token
		{
			const res = await fetch(`/sanctum/csrf-cookie`);
			if (!res.ok) {
				// Log error
				loading = false;
				return;
			}
		}

		const res = await fetch(`/api/login`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				email: email,
				password: password
			}),
			credentials: 'include'
		});

		if (!res.ok) {
			// Log error
			loading = false;
			return;
		}

		goto(resolve('/dashboard'));
	}
</script>

<Card.Root class="mx-auto w-full max-w-sm">
	<Card.Header>
		<Card.Title class="text-2xl">Login</Card.Title>
		<Card.Description>Enter your email below to login to your account</Card.Description>
	</Card.Header>
	<Card.Content>
		<form onsubmit={login}>
			<FieldGroup>
				<Field>
					<FieldLabel for="email-{id}">Email</FieldLabel>
					<Input
						id="email-{id}"
						type="email"
						bind:value={email}
						placeholder="m@example.com"
						required
					/>
				</Field>
				<Field>
					<div class="flex items-center">
						<FieldLabel for="password-{id}">Password</FieldLabel>
						<a href="##" class="ms-auto inline-block text-sm underline"> Forgot your password? </a>
					</div>
					<Input id="password-{id}" type="password" bind:value={password} required />
				</Field>
				<Field>
					<Button type="submit" class="w-full" {loading}>Login</Button>
					<FieldDescription class="text-center">
						Don't have an account? <a href="##">Sign up</a>
					</FieldDescription>
				</Field>
			</FieldGroup>
		</form>
	</Card.Content>
</Card.Root>
