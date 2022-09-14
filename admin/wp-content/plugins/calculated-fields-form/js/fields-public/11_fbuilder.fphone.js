	$.fbuilder.controls[ 'fPhone' ]=function(){};
	$.extend(
		$.fbuilder.controls[ 'fPhone' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Phone",
			ftype:"fPhone",
			required:false,
			readonly:false,
			dformat:"### ### ####",
			predefined:"888 888 8888",
			show:function()
				{
					this.predefined = new String(this._getAttr('predefined', true));
					var me   = this,
						str  = "",
						tmp  = this.dformat.split(' '),
						tmpv = this.predefined.split(' '),
						attr = ( typeof this.predefinedClick != 'undefined' && this.predefinedClick ) ? 'placeholder' : 'value';

					for (var i=0;i<tmpv.length;i++)
					{
						if ($.trim(tmpv[i])=="")
						{
							tmpv.splice(i,1);
						}
					}

					for (var i=0;i<tmp.length;i++)
					{
						if ($.trim(tmp[i])!="")
						{
							str += '<div class="uh_phone" ><input aria-label="'+$.fbuilder.htmlEncode(this.title)+'" type="text" id="'+this.name+'_'+i+'" name="'+this.name+'_'+i+'" class="field '+((i==0) ? ' phone ' : ' digits ')+((this.required) ? ' required ' : '')+'" size="'+$.trim(tmp[i]).length+'" '+attr+'="'+((tmpv[i])?tmpv[i]:"")+'" maxlength="'+$.trim(tmp[i]).length+'" minlength="'+$.trim(tmp[i]).length+'" '+((this.readonly)?'readonly':'')+' /><div class="l">'+$.trim(tmp[i])+'</div></div>';
						}
					}

					return '<div class="fields '+this.csslayout+' '+this.name+' cff-phone-field" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input type="hidden" id="'+this.name+'" name="'+this.name+'" class="field " '+attr+'="'+tmpv.join('')+'" />'+str+'<div class="clearer"></div><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';
				},
            after_show: function()
				{
					var me   = this,
						tmp  = me.dformat.split(' ');

					if(!('phone' in $.validator.methods))
						$.validator.addMethod("phone", function(value, element)
						{
							if( this.optional( element ) ) return true;
							else return /^\+{0,1}\d*$/.test(value);
						});

					for (var i = 0, h = tmp.length; i < h; i++ )
					{
						$( '#'+me.name+'_'+i ).bind( 'change', function(){
							var v = '';
							for( var i = 0; i < tmp.length; i++ )
							{
								v += $( '#'+me.name+'_'+i ).val();
							}
							$( '#'+me.name ).val( v ).change();
						} );
						if( i+1 < h )
						{
							$('#'+me.name+'_'+i).bind( 'keyup', { 'next': i+1 }, function( evt ){
								var e = $( this );
								if( e.val().length == e.attr( 'maxlength' ) )
								{
									e.change();
									$( '#'+me.name+'_'+evt.data.next ).focus();
								}
							} );
						}
					}
				},
			val:function()
				{
					var e = $( '[id="' + this.name + '"]:not(.ignore)' ),
						p = $.fbuilder.parseValStr(e.val(), true);

					if( e.length ) return ($.isNumeric(p)) ? '"'+p+'"' : p;
					return 0;
				},
			setVal:function( v )
				{
					$( '[name="'+this.name+'"]' ).val( v );
					v = $.trim( v ).replace( /[^\d]/g, ' ').split( ' ' );
					for( var i in v ) $( '[id="' + this.name + '_' + i + '"]' ).val( v[ i ] );
				}
		}
	);