<form action="" method="post">
	<fieldset>
		<ul>
			{include file="Form/elementsLoop.tpl"}
			<li id="groupTimes">
				<label>Times</label>
				<ul>
					{foreach $elements.timeslots.value as $timeslot} 
						<li>{html_select_time time=$timeslot display_seconds=false minute_interval=15 field_separator=":" field_array="timeslots[`$timeslot@index`]" prefix=""}<a class="deleteRow" href="">Delete</a></li>
					{/foreach}
				</ul>
				<a class="addRow" href="">Add another time</a>
			</li>
			<li>
				<label>&nbsp</label>
				<button type="submit">Submit</button>
				<a href="/">Cancel</a>
			</li>
		</ul>
	</fieldset>
</form>