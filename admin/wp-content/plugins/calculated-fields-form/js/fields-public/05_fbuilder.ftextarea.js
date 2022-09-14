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
			readonly:false,
			size:"medium",
			minlength:"",
			maxlength:"",
            rows:4,
			show:function()
				{
					this.minlength = $.fbuilder.htmlEncode($.trim(this.minlength));
					this.maxlength = $.fbuilder.htmlEncode($.trim(this.maxlength));
					this.predefined = this._getAttr('predefined');
					return '<div class="fields '+this.csslayout+' '+this.name+' cff-textarea-field" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><textarea aria-label="'+$.fbuilder.htmlEncode(this.title)+'" '+((!/^\s*$/.test(this.rows)) ? 'rows='+this.rows : '' )+' id="'+this.name+'" name="'+this.name+'"'+((this.minlength.length) ? ' minlength="'+this.minlength+'"' : '')+((this.maxlength.length) ? ' maxlength="'+this.maxlength+'"' : '')+' class="field '+this.size+((this.required)?" required":"")+'" '+((this.readonly)?'readonly':'')+' autocomplete="'+this.autocomplete+'">'+((!this.predefinedClick) ? this.predefined : '')+'</textarea><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
				},
			val:function(raw)
				{
					raw = raw || false;
					var e = $( '[id="' + this.name + '"]:not(.ignore)' );
					if( e.length ) return $.fbuilder.parseValStr(e.val().replace(/[\n\r]+/g, ' '), raw);
					return 0;
				}
		}
	);