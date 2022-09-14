	$.fbuilder.controls[ 'ftext' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'ftext' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Untitled",
			ftype:"ftext",
            autocomplete:"off",
			predefined:"",
			predefinedClick:false,
			required:false,
			readonly:false,
			size:"medium",
			minlength:"",
			maxlength:"",
			equalTo:"",
			regExp:"",
			regExpMssg:"",
			show:function()
				{
					this.minlength = $.fbuilder.htmlEncode($.trim(this.minlength));
					this.maxlength = $.fbuilder.htmlEncode($.trim(this.maxlength));
					this.equalTo = $.fbuilder.htmlEncode($.trim(this.equalTo));
					this.predefined = this._getAttr('predefined');
					return '<div class="fields '+this.csslayout+' '+this.name+' cff-text-field" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input aria-label="'+$.fbuilder.htmlEncode(this.title)+'" id="'+this.name+'" name="'+this.name+'"'+((this.minlength.length) ? ' minlength="'+(this.minlength)+'"' : '')+((this.maxlength.length) ? ' maxlength="'+(this.maxlength)+'"' : '')+((this.equalTo.length) ? ' equalTo="#'+this.equalTo+this.form_identifier+'"':'' )+' class="field '+this.size+((this.required)?" required":"")+'" '+((this.readonly)?'readonly':'')+' type="text" value="'+$.fbuilder.htmlEncode(this.predefined)+'" autocomplete="'+this.autocomplete+'" /><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
				},
			after_show:function()
				{
					if( this.regExp != "" && typeof $[ 'validator' ] != 'undefined' )
					{
						var parts 	= this.regExp.match(/(\/)(.*)(\/)([gimy]{0,4})$/i);
						this.regExp = ( parts === null ) ? new RegExp(this.regExp) : new RegExp(parts[2],parts[4].toLowerCase());

						if(!('pattern' in $.validator.methods))
							$.validator.addMethod( 'pattern', function( value, element, param )
								{
									try{
										return this.optional(element) || param.test( value );
									}
									catch(err){return true;}
								}
							);
						$('#'+this.name).rules('add',{'pattern':this.regExp, messages:{'pattern':this.regExpMssg}});
					}
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