<?php
error_reporting(E_ERROR|E_PARSE);
header('Content-Type: application/x-javascript; charset=UTF-8');
?>
fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery.fbuilderjQueryGenerator = function(){
	if('fbuilderGeneratorFlag' in fbuilderjQuery) return;
	(function($) {
		// Namespace of fbuilder
		$.fbuilder = $.fbuilder || {};
        $.fbuilder.default_template = '<?php print esc_js(CP_CALCULATEDFIELDSF_DEFAULT_template); ?>';
		$.fbuilder[ 'objName' ] = 'fbuilderjQuery';
<?php
	// Load Module files
	try
	{
        $md = dir( dirname( __FILE__ )."/modules");
		$modules_files = array();
        while( false !== ( $entry = $md->read() ) )
		{
            if ( strlen( $entry ) > 3 && is_dir( $md->path.'/'.$entry ) )
			{
				if ( file_exists( $md->path.'/'.$entry.'/admin' ) )
				{
					$m = dir( $md->path.'/'.$entry.'/admin' );
					while( false !== ( $mentry = $m->read() ) )
					{
						if( strlen( $mentry ) > 3 && strtolower( substr( $mentry, strlen( $mentry ) - 3 ) ) == '.js' )
						{
							$modules_files[] = $m->path.'/'.$mentry;
						}
					}
				}

			}
        }
		sort( $modules_files );
		foreach( $modules_files as $file )
		{
			require $file;
		}
	}
	catch (Exception $e)
	{
        // ignore the error
    }

	// Load Control files
    require 'fbuilder-pro-admin.jquery.js';
    try
	{
        $d = dir( dirname( __FILE__ )."/fields-admin" );
		$controls_files = array();
		while (false !== ($entry = $d->read()))
		{
            if (strlen($entry) > 3 && strtolower(substr($entry,strlen($entry)-3)) == '.js')
			{
				if (file_exists( $d->path.'/'.$entry ) )
				{
                    $controls_files[] =  $d->path.'/'.$entry;
				}
			}
        }
		sort( $controls_files );
		foreach( $controls_files as $file )
		{
			require $file;
		}

		do_action('cpcff_load_controls_admin');
	}
	catch (Exception $e)
	{
        // ignore the error
    }

    do_action( 'cpcff_additional_admin_scripts' );
?>
	})(fbuilderjQuery);
	fbuilderjQuery.fbuilderGeneratorFlag = 1;
};
fbuilderjQuery(fbuilderjQuery.fbuilderjQueryGenerator);
fbuilderjQuery(window).on('load',fbuilderjQuery.fbuilderjQueryGenerator);