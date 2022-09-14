/*
* managing_fields.js v0.1
* By: CALCULATED FIELD PROGRAMMERS
* The script allows managing fields
* Copyright 2015 CODEPEOPLE
* You may use this project under MIT or GPL licenses.
*/

;(function(root){
	var lib = {};
	lib.cf_processing_version = '0.1';

	/*** PRIVATE FUNCTIONS ***/

	function _getForm(_form)
	{
		if(typeof _form == 'undefined'){
			if('currentFormId' in fbuilderjQuery.fbuilder) _form = fbuilderjQuery.fbuilder.currentFormId;
			else return '_1';
		}
		if(/^_\d*$/.test(_form)) return _form;
		if(/^\d*$/.test(_form)) return '_'+_form;
		return $((typeof _form == 'object') ? _form : '#'+_form).find('[name="cp_calculatedfieldsf_pform_psequence"]').val();
	}

	function _getField( _field, _form )
	{
		return $.fbuilder['forms'][_getForm(_form)].getItem(_field);
	}

	/*** PUBLIC FUNCTIONS ***/
    lib.getField = function(_field, _form)
    {
        return _getField(_field, _form);
    };

	lib.activatefield = lib.ACTIVATEFIELD = function( _field, _form )
	{
		var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f)
		{
			j = f.jQueryRef();
			if(j.find('[id*="'+f.name+'"]').hasClass('ignore'))
			{
				j.add(j.find('.fields')).show();
				if(f.name in $.fbuilder.forms[o].toHide) delete $.fbuilder.forms[o].toHide[f.name];
				if(!(f.name in $.fbuilder.forms[o].toShow)) $.fbuilder.forms[o].toShow[f.name] = {'ref': {}};
				j.find('[id*="'+f.name+'"]').removeClass('ignore').change();
				$.fbuilder.showHideDep({'formIdentifier':o,'fieldIdentifier':f.name});
			}
		}
	};

	lib.ignorefield = lib.IGNOREFIELD = function( _field, _form )
	{
		var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f)
		{
			j = f.jQueryRef();
			if(!j.find('[id*="'+f.name+'"]').hasClass('ignore'))
			{
				j.add(j.find('.fields')).hide();
				if(!(f.name in $.fbuilder.forms[o].toHide)) $.fbuilder.forms[o].toHide[f.name] = {};
				if(f.name in $.fbuilder.forms[o].toShow) delete $.fbuilder.forms[o].toShow[f.name];
				j.find('[id*="'+f.name+'"]').addClass('ignore').change();
				$.fbuilder.showHideDep({'formIdentifier':o,'fieldIdentifier':f.name});
			}
		}
	};

	lib.disableequations = lib.DISABLEEQUATIONS = function(f)
	{
		jQuery(f || '[id*="cp_calculatedfieldsf_pform_"]').attr('data-evalequations',0);
	};

	lib.enableequations = lib.ENABLEEQUATIONS = function(f)
	{
		jQuery(f || '[id*="cp_calculatedfieldsf_pform_"]').attr('data-evalequations',1);
	};

	lib.EVALEQUATIONS = lib.evalequations = function(f)
	{
		fbuilderjQuery.fbuilder.calculator.defaultCalc(f);
	};

	lib.EVALEQUATION = lib.evalequation = function(f,e)
	{
		var c = fbuilderjQuery.fbuilder.calculator, eq, fi, i;
		if(typeof e == 'undefined') c.defaultCalc(f);
		else
		{
			if(Number.isInteger(e)) e = 'fieldname'+e;
			if('equations' in f)
			{
				for(i in f.equations)
				{
					eq = f.equations[i];
					fi = eq.identifier;
					if(eq.result == e+fi){
						c.enqueueEquation(fi, [eq]);
						c.processQueue(fi);
						return;
					}
				}
			}
		}
	};

    lib.COPYFIELDVALUE = lib.copyfieldvalue = function(_field, _form)
    {
        var o = _getForm(_form), f = _getField(_field, _form), j;
		if(f)
		{
			j = f.jQueryRef().find(':input:eq(0)');
			if(j.length)
			{
                try
                {
                    j.select();
                    document.execCommand('copy');
                } catch(err){}
			}
		}
    };

    lib.gotopage = lib.GOTOPAGE = lib.goToPage = function(p, f)
    {
        try
        {
            var o = $('#'+$.fbuilder['forms'][_getForm(f)].formId), c;
            if(o.length)
            {
                c = o.find('.pbreak:visible').attr('page');
                $.fbuilder.goToPage({'form':o,'from':c,'to':p});
            }
        } catch(err) { if(typeof console != 'undefined') console.log(err); }
    };

    if(window.PRINTFORM == undefined)
    {
        lib.printform = lib.PRINTFORM = function(show_pages)
        {
            var o = $('#'+$.fbuilder['forms'][_getForm()].formId);
            if(o.length)
            {
                o.addClass('cff-print');
                if(!!show_pages) o.find('.pbreak').addClass('cff-print');
                while(o.length)
                {
                    o.siblings().addClass('cff-no-print');
                    o = o.parent();
                }
            }
            window.print();
            setTimeout(function(){
                jQuery('.cff-no-print').removeClass('cff-no-print');
                jQuery('.cff-print').removeClass('cff-print');
            }, 5000);
        };

    }
	root.CF_FIELDS_MANAGEMENT = lib;

})(this);