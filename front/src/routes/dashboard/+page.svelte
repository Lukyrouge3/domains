<script lang="ts">
	import * as Table from '$lib/components/ui/table/index.js';
	import * as Card from '$lib/components/ui/card/index.js';
	import Button from '$lib/components/button.svelte';
	import * as Terminal from '$lib/components/ui/terminal';

	const invoices = [
		{
			id: 1,
			host: 'deneria.net',
			records: 5,
			lastChecked: new Date().toUTCString()
		}
	];

	let c = $state(0);

	let domainData = $state();
	let fetchDomainHost: string | null = $state(null);
	let fetchPromise: Promise<any> | null = $state(null);
	async function viewDomain(domainId: number) {
		console.log('viewDomain called with domainId:', domainId);

		const domain = invoices.find((d) => d.id === domainId);
		if (domain) {
			fetchDomainHost = domain.host;
		}

		fetchPromise = fetch(`/api/dns-entries`);
		c++;
		const res = await fetchPromise;
		if (!res.ok) {
			console.error('Failed to fetch domain data:', res.statusText);
			return;
		}

		const data = await res.json();
		domainData = data;
	}
</script>

<div class="m-8 flex flex-row gap-4">
	<Card.Root class="flex flex-col gap-2">
		<Card.Header>
			<Card.Title>Monitored Domains</Card.Title>
			<Card.Action>
				<Button variant="default" class="text-white">Add</Button>
			</Card.Action>
		</Card.Header>
		<Card.Content>
			<Table.Root class="max-w-2xl">
				<Table.Caption>The domains you are currently monitoring</Table.Caption>
				<Table.Header>
					<Table.Row>
						<Table.Head class="w-25">Host</Table.Head>
						<Table.Head>Records</Table.Head>
						<Table.Head>Last Checked</Table.Head>
						<Table.Head></Table.Head>
					</Table.Row>
				</Table.Header>
				<Table.Body>
					{#each invoices as domain (domain.id)}
						<Table.Row>
							<Table.Cell class="font-medium">{domain.host}</Table.Cell>
							<Table.Cell>{domain.records}</Table.Cell>
							<Table.Cell>{domain.lastChecked}</Table.Cell>
							<Table.Cell class="text-end">
								<!-- <a
									href={resolve(`/dashboard/domains/${domain.id}`)}
									class="text-blue-500 hover:underline">View</a
								> -->
								<Button variant="link" class="text-white" onclick={() => viewDomain(domain.id)}
									>View</Button
								>
							</Table.Cell>
						</Table.Row>
					{/each}
				</Table.Body>
				<!-- <Table.Footer>
					<Table.Row>
						<Table.Cell colspan={4}><Button class="w-full">Add Domain</Button></Table.Cell>
					</Table.Row>
				</Table.Footer> -->
			</Table.Root>
		</Card.Content>
	</Card.Root>
	{#key c}
		<Terminal.Root class="flex-1" speed={2}>
			{#if fetchDomainHost}
				<Terminal.TypingAnimation>&gt; fetch domain {fetchDomainHost}</Terminal.TypingAnimation>

				<Terminal.LoadingPromise promise={fetchPromise} delay={500}>
					{#snippet loadingMessage()}
						Fetching manifest
					{/snippet}
					{#snippet completeMessage()}
						{#if domainData}
							<span class="text-green-500">
								✔ Retrieved dns-entries for {fetchDomainHost}
							</span>
						{:else}
							<span class="text-red-500">
								✖ No data found for {fetchDomainHost}
							</span>
						{/if}
					{/snippet}
				</Terminal.LoadingPromise>

				<Terminal.Loading delay={500} duration={1000}>
					{#snippet loadingMessage()}
						Loading...
					{/snippet}
					{#snippet completeMessage()}
						{#if domainData}
							<pre>
                                {#each domainData as entry (entry)}
									<span>{entry.class}&#9;{entry.type}&#9;{entry.ip}&#9;{entry.expires_at}</span>
								{/each}
                            </pre>
						{/if}
					{/snippet}
				</Terminal.Loading>
			{/if}
		</Terminal.Root>
	{/key}
</div>
