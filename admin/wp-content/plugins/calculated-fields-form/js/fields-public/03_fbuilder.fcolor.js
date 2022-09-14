	$.fbuilder.controls[ 'fcolor' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fcolor' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Untitled",
			ftype:"fcolor",
			predefined:"",
			predefinedClick:false,
			required:false,
			readonly:false,
			size:"medium",
			show:function()
				{
					this.predefined = this._getAttr('predefined');
					return '<div class="fields '+this.csslayout+' '+this.name+' cff-color-field" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input aria-label="'+$.fbuilder.htmlEncode(this.title)+'" id="'+this.name+'" name="'+this.name+'"'+' class="field '+this.size+((this.required)?" required":"")+'" '+((this.readonly)?'readonly':'')+' type="color" value="'+$.fbuilder.htmlEncode(this.predefined)+'" /><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
				},
			after_show:function()
				{
				},
			val:function(raw)
				{
					raw = raw || false;
					var e = $( '[id="' + this.name + '"]:not(.ignore)' );
					if( e.length ) return $.fbuilder.parseValStr( e.val(),  raw );
					return 0;
				}
		}
	);