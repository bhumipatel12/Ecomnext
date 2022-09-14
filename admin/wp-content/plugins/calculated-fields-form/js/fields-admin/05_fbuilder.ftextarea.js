	$.fbuilder.typeList.push(
		{
			id:"ftextarea",
			name:"Text Area",
			control_category:1
		}
	);
	$.fbuilder.controls[ 'ftextarea' ] = function(){};
	$.extend(
		$.fbuilder.controls[ 'ftextarea' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Untitled",
			ftype:"ftextarea",
            autocomplete:"off",
			predefined:"",
			predefinedClick:false,
			required:false,
			exclude:false,
			readonly:false,
			size:"medium",
			minlength:"",
			maxlength:"",
            rows:4,
			display:function()
				{
					return $.fbuilder.sanitize('<div class="fields '+this.name+' '+this.ftype+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.name+'"><div class="arrow ui-icon ui-icon-play "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><label>'+this.title+''+((this.required)?"*":"")+'</label><div class="dfield"><textarea '+((!/^\s*$/.test(this.rows)) ? 'rows='+this.rows : '' )+' class="field disabled '+this.size+'">'+this.predefined+'</textarea><span class="uh">'+this.userhelp+'</span></div><div class="clearer" /></div>');
				},
			editItemEvents:function()
				{
					var evt = [
							{s:"#sSize",e:"change", l:"size"},
							{s:"#sMinlength",e:"change keyup", l:"minlength"},
							{s:"#sMaxlength",e:"change keyup", l:"maxlength"},
							{s:"#sRows",e:"change keyup", l:"rows"}
						];
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this, evt);
				},
			showSpecialDataInstance: function()
				{
					return '<div class="column width50"><label>Min length/characters</label><input type="text" name="sMinlength" id="sMinlength" value="'+$.fbuilder.htmlEncode(this.minlength)+'" class="large"></div><div class="column width50"><label>Max length/characters</label><input type="text" name="sMaxlength" id="sMaxlength" value="'+$.fbuilder.htmlEncode(this.maxlength)+'" class="large"></div><div class="clearer" /><label>Number of rows</label><input type="text" name="sRows" id="sRows" value="'+$.fbuilder.htmlEncode(this.rows)+'" />';
				}
	});