<script lang="ts" generics="T">
	import { cn } from '$lib/utils.js';
	import { onDestroy } from 'svelte';
	import { useAnimation } from './terminal.svelte.js';
	import { fly } from 'svelte/transition';
	import type { Snippet } from 'svelte';
	import { box } from 'svelte-toolbelt';

	const frames = ['◒', '◐', '◓', '◑'];

	type TerminalLoadingAsyncProps = {
		delay?: number;
		promise: Promise<T>;
		loadingMessage: Snippet;
		completeMessage: Snippet<[T]>;
		errorMessage?: Snippet<[unknown]>;
		class?: string;
	};

	let {
		delay = 0,
		promise,
		loadingMessage,
		completeMessage,
		errorMessage,
		class: className
	}: TerminalLoadingAsyncProps = $props();

	let playAnimation = $state(false);
	let animationSpeed = $state(1);
	let frameIndex = $state(0);
	let complete = $state(false);
	let errored = $state(false);
	let result = $state<T>();
	let error = $state<unknown>();
	let interval = $state<ReturnType<typeof setInterval>>();

	const play = (speed: number) => {
		playAnimation = true;
		animationSpeed = speed;

		interval = setInterval(nextFrame, 75 / animationSpeed);

		promise
			.then((value) => {
				result = value;
				complete = true;
				animation.onComplete?.();
			})
			.catch((err) => {
				error = err;
				errored = true;
				animation.onComplete?.();
			})
			.finally(() => {
				clearInterval(interval);
			});
	};

	const nextFrame = () => {
		if (frameIndex >= frames.length - 1) {
			frameIndex = 0;
			return;
		}

		frameIndex++;
	};

	const flyDuration = $derived(300 / animationSpeed);

	const animation = useAnimation({ delay: box.with(() => delay), play });

	onDestroy(() => {
		animation.dispose();
		clearInterval(interval);
	});
</script>

{#if playAnimation && !complete && !errored}
	<span class={cn('block', className)} in:fly={{ y: -5, duration: flyDuration }}>
		<span class="text-cyan-400">{frames[frameIndex]}</span>
		{@render loadingMessage()}
	</span>
{:else if playAnimation && complete}
	<span class={cn('block', className)} data-completed in:fly={{ y: -5, duration: flyDuration }}>
		{@render completeMessage(result as T)}
	</span>
{:else if playAnimation && errored}
	<span class={cn('block', className)} data-error in:fly={{ y: -5, duration: flyDuration }}>
		{#if errorMessage}
			{@render errorMessage(error)}
		{:else}
			{@render completeMessage(result as T)}
		{/if}
	</span>
{/if}
