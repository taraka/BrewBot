{include file="header.tpl"}
	
	<div class="content">
		<h1>Welcome</h1>

		{if $optedOut}
			<p>You are currenty opted out from BrewBot</p>
			<p><a href="/user/optin">I would like to be part of the BrewBot again</a></p>
		{else}
			{if $groupCount}
				<h3>Which group are you here to manage?</h3>
				<ul>
					{foreach $groups as $group}
						<li><a href="/group/edit/{$group.id}">{$group.name}</a></li>
					{/foreach}
				</ul>
				<p><a href="/group/create/">Create a new group</a></p>
			{else}
				<p>You don't have any brew groups yet, why not <a href="/group/create/">create one</a>.</p>
			{/if}
		
			<p><a href="/user/optout/">STOP THE MADNESS!!!!</a></p>
		{/if}
	</div>
	<!-- /content -->

{include file="footer.tpl"}