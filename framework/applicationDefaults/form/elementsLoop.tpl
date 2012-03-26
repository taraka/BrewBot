{foreach from=$elements item=element}
	{if !$element.hidden && $element.name}
		<li class="{$element.name}_listItem{if $element.error} error{/if}">
			{if $element.label}
				<label for="{$element.name}_label">{$element.label}</label>
			{/if}
			{if $element.element == 'input'}
				{if $element.type == 'radio'}
					{foreach from=$element.options item=option key=key}
						<input type="{$element.type}" name="{$element.name}" id="{$element.name}_label" value="{$key}" {if $element.value == $key}checked="checked"{/if} /> {$option}
					{/foreach}
				{elseif $element.type == 'file'}
					<div class="upload">
						<button type="button">Attach files</button>
						<span id="uploadButton">Attach files</span>
						<div id="queuedFiles"></div>
					</div>
				{elseif $element.type == 'date'}
					{if !$element.value}
						{html_select_date field_array=$element.name start_year=-5 end_year=+5}
					{else}
						{html_select_date field_array=$element.name start_year=-5 end_year=+5 time=$element.value}
					{/if}
				{elseif $element.type == 'checkbox'}
					<input type="{$element.type}" name="{$element.name}" id="{$element.name}_label" {if $element.checked}checked="checked"{/if}/>
				{else}
					<input type="{$element.type}" name="{$element.name}" id="{$element.name}_label" value="{$element.value}"{if $element.placeholder} placeholder="{$element.placeholder}"{/if} />
				{/if}
			{elseif $element.element == 'textarea'}
				<textarea name="{$element.name}" id="{$element.name}_label">{$element.value}</textarea>
			{elseif $element.element == 'select'}
				<select name="{$element.name}" id="{$element.name}_label">
					<option value="0">Please select</option>
					{foreach from=$element.options item=option}
						<option value="{$option.id}"{if $element.value == $option.id} selected="selected"{/if}>{$option.name}</option>
					{/foreach}
				</select>
			{/if}
			
			{if $element.hint}
				<span class="hint">{$element.hint}</span>
			{/if}
			{if $element.error}
				<span class="errorMessage">{$element.error}</span>
			{/if}
		</li>
	{/if}
{/foreach}