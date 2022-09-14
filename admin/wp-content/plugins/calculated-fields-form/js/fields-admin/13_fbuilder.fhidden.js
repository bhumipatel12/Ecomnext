	$.fbuilder.typeList.push(
		{
			id:"fhidden",
			name:"Hidden",
			control_category:1
		}
	);
	$.fbuilder.controls[ 'fhidden' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fhidden' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Hidden",
			ftype:"fhidden",
			exclude:false,
			predefined:"",
			display:function()
				{
					return $.fbuilder.sanitize('<div class="fields '+this.name+' '+this.ftype+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.name+'"><div class="arrow ui-icon ui-icon-play "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><label>'+this.title+'</label><span class="uh">'+this.predefined+'</span><div class="clearer" /></div>');
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this);
				},
			showTitle: function(f)
				{
					return '<div><label>Field Label</label><textarea class="large" name="sTitle" id="sTitle">'+this.title+'</textarea><span class="uh">The field label is only for the admin section, never for the public page</span></div>';
				},
			showUserhelp:function(){ return ''; },
			showPredefined: function()
				{
					return '<div><label>Value</label><textarea class="large" name="sPredefined" id="sPredefined">'+this.predefined+'</textarea></div>';
				}
	});