
var GroupEdit = Class.create({
	initialize: function()
	{
		this.addedCount = 0;
	},
	
	start: function()
	{
		$('groupTimes').select('a.deleteRow').each(function(link){
			link.observe('click', this.deleteClicked.bind(this));
		}.bind(this));
		
		$('groupTimes').select('a.addRow').each(function(link){
			link.observe('click', this.addClicked.bind(this));
		}.bind(this));
	},
	
	deleteClicked: function(event)
	{
		event.stop();
		event.element().up('li').remove();
	},
	
	addClicked: function(event)
	{
		event.stop();
		
		var index = "n" + this.addedCount++;
		
		var li = new Element('li');
		$('groupTimes').down('ul').insert({bottom: li});
		
		var hours = new Element('select', {name: "timeslots[" + index + "][Hour]"});
		li.insert(hours);
		
		$R(00, 23).each(function(value){
			var option = new Element('option', {value: value}).update(value);
			hours.insert(option);
		});
		
		var span = new Element('span').update(":");
		li.insert(span);
		
		var minutes = new Element('select', {name: "timeslots[" + index + "][Minute]"});
		li.insert(minutes);
		
		$w("00 15 30 45").each(function(value){
			var option = new Element('option', {value: value}).update(value);
			minutes.insert(option);
		});
		
		var deleteLink = new Element('a', {className: 'deleteRow'}).update('Delete');
		deleteLink.observe('click', this.deleteClicked.bind(this));
		li.insert(deleteLink);
	}
});

document.observe('dom:loaded', function() {
	if ($('groupTimes'))
	{
		var ui = new GroupEdit();
		ui.start();
	}
});