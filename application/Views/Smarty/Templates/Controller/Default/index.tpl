{include file="header.tpl"}
	
	<div class="content">
		<h1>Welcome</h1>

		{if $groupCount}
			<h3>Which group are you here to manage?</h3>
			<ul>
				{foreach $groups as $group}
					<li><a href="/group/edit/{$group.id}">{$group.name}</a></li>
				{/foreach}
			</ul>
		{else}
			<p>You don't have any brew groups yet, why not <a href="/group/create/">create one</a>.</p>
		{/if}
		
	</div>
	<!-- /content -->

{include file="footer.tpl"}