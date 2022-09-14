	$.fbuilder.typeList.push(
		{
			id:"fPhone",
			name:"Phone field",
			control_category:1
		}
	);
	$.fbuilder.controls[ 'fPhone' ] = function(){};
	$.extend(
		$.fbuilder.controls[ 'fPhone' ].prototype,
		$.fbuilder.controls[ 'ffields' ].prototype,
		{
			title:"Phone",
			ftype:"fPhone",
			required:false,
			exclude:false,
			readonly:false,
			dformat:"### ### ####",
			predefined:"888 888 8888",
			display:function()
				{
					var str = "";
					var tmp = this.dformat.split(' ');
					var tmpv = this.predefined.split(' ');
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
							str += '<div class="uh_phone" ><input type="text" class="field disabled" style="width:'+(15*$.trim(tmp[i]).length)+'px" value="'+((tmpv[i])?tmpv[i]:"")+'" maxlength="'+$.trim(tmp[i]).length+'" /><div class="l">'+$.trim(tmp[i])+'</div></div>';
						}
					}
					return $.fbuilder.sanitize('<div class="fields '+this.name+' '+this.ftype+'" id="field'+this.form_identifier+'-'+this.index+'" title="'+this.name+'"><div class="arrow ui-icon ui-icon-play "></div><div title="Delete" class="remove ui-icon ui-icon-trash "></div><div title="Duplicate" class="copy ui-icon ui-icon-copy "></div><label>'+this.title+''+((this.required)?"*":"")+'</label><div class="dfield">'+str+'<span class="uh">'+this.userhelp+'</span></div><div class="clearer" /></div>');
				},
			editItemEvents:function()
				{
					var evt = [{s:"#sFormat",e:"change keyup", l:"dformat"}];
					$.fbuilder.controls[ 'ffields' ].prototype.editItemEvents.call(this,evt);
				},
			showFormatIntance: function()
				{
					return '<label>Number Format</label><input type="text" name="sFormat" id="sFormat" value="'+$.fbuilder.htmlEncode(this.dformat)+'" class="large" />';
				}
	});