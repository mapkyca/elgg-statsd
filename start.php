<?php
	/**
	 * StatsD for Elgg
	 * Provides detailed performance and system health information about Elgg via a Node.JS statsd server.
	 *
	 * @licence GNU Public License version 2
	 * @link https://github.com/mapkyca/elgg-statsd
	 * @link http://www.marcus-povey.co.uk
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 */
	
	// Include statsd library
	require_once(dirname(__FILE__) . '/lib/statsd.php');
	
	function statsd_init()
	{
            global $CONFIG;
            
            global $__STATSD_COUNTERS; $__STATSD_COUNTERS = array();
            
            // Work out default bucket
            if (!$CONFIG->statsd_bucket)
                $CONFIG->statsd_bucket = elgg_get_plugin_setting('bucket', 'elgg-statsd');
            if (!$CONFIG->statsd_bucket) {
                $CONFIG->statsd_bucket = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", elgg_get_site_entity()->name));
            }
            
            // Sanitise bucket
            $CONFIG->statsd_bucket = trim($CONFIG->statsd_bucket, ' .,');
            
            if (
                    (elgg_get_plugin_setting('pluginenabled', 'elgg-statsd') == 'yes') && 
                    (elgg_get_plugin_setting('host', 'elgg-statsd')) && 
                    (elgg_get_plugin_setting('port', 'elgg-statsd'))
            ) {
                
                // Log page impressions
                if (elgg_get_plugin_setting('log_impressions', 'elgg-statsd')!='no')
                    ElggStatsD::increment("{$CONFIG->statsd_bucket}.impressions");

                // Listen to hooks and events
                if (elgg_get_plugin_setting('log_hooks', 'elgg-statsd')!='no')
                    elgg_register_plugin_hook_handler('all', 'all', 'statsd_log_hook_triggered', 1);
                if (elgg_get_plugin_setting('log_events', 'elgg-statsd')!='no')
                    elgg_register_event_handler('all', 'all', 'statsd_log_event_triggered', 1);

                // Replace error handlers with our own
                set_error_handler('statsd_php_error_handler');
                set_exception_handler('statsd_php_exception_handler');
               
                
                // Shutdown hook
                register_shutdown_function('statsd_shutdown');
            }
	}
	
        function statsd_log_hook_triggered($hook, $entity_type, $returnvalue, $params)
        {
            global $CONFIG, $__STATSD_COUNTERS;
            
            $__STATSD_COUNTERS["{$CONFIG->statsd_bucket}.hooks.$hook.$entity_type"]++;
            
            //ElggStatsD::increment("{$CONFIG->statsd_bucket}.hooks.$hook.$entity_type");
            
        }
        
        function statsd_log_event_triggered($event, $type, $object) {
            global $CONFIG, $__STATSD_COUNTERS;

            // Log Events
            $subtype = "";
            if (($object) && (elgg_instanceof($object))) {
                $subtype = get_subtype_from_id($object->subtype);
                $subtype = ".$subtype";
            }
            
            $__STATSD_COUNTERS["{$CONFIG->statsd_bucket}.events.$type.$event{$subtype}"]++;
            //ElggStatsD::increment("{$CONFIG->statsd_bucket}.events.$type.$entity_type{$subtype}");
            
        }
	
        function statsd_php_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
            
            global $CONFIG, $__STATSD_COUNTERS;
            
            switch ($errno) {
		case E_USER_ERROR:
                    if (elgg_get_plugin_setting('log_errors', 'elgg-statsd')=='no') 
                        break;
                
                    ElggStatsD::increment("{$CONFIG->statsd_bucket}.php.errors");
		break;

		case E_WARNING :
		case E_USER_WARNING :
		case E_RECOVERABLE_ERROR: // (e.g. type hint violation)
                    if (elgg_get_plugin_setting('log_warnings', 'elgg-statsd')!='yes') 
                        break;
                
                    $__STATSD_COUNTERS["{$CONFIG->statsd_bucket}.php.warnings"];

		default:
                    // Count notices
                    if (elgg_get_plugin_setting('log_notices', 'elgg-statsd')!='yes') 
                        break;
                    
                    $__STATSD_COUNTERS["{$CONFIG->statsd_bucket}.php.notices"]++;
                    
            }
            
            // Bounce to elgg handler
            return _elgg_php_error_handler($errno, $errmsg, $filename, $linenum, $vars);
        }
        
        function statsd_php_exception_handler($exception) {
            
            global $CONFIG;
            
            if (elgg_get_plugin_setting('log_exceptions', 'elgg-statsd')!='no') 
            { 
                statsd_increment("exceptions");
                statsd_increment("exceptions.".get_class($exception));
            }
            
            // Bounce to elgg handler
            return _elgg_php_exception_handler($exception);
        }
        
        /**
         * Submit aggregate of busy queries on shutdown
         */
        function statsd_shutdown()
        {
            global $CONFIG, $__STATSD_COUNTERS, $START_MICROTIME;;
            
            foreach ($__STATSD_COUNTERS as $key => $count) {
                
                // Normalise key for upstream submission
                $key = preg_replace("/[^a-zA-Z0-9\s]/", ".", $key);
                
                ElggStatsD::updateStats($key, $count);
            }
            
            // Now log time script execution took
            if (elgg_get_plugin_setting('log_time', 'elgg-statsd')!='no')
                ElggStatsD::timing("{$CONFIG->statsd_bucket}.executiontime", (microtime(true) - $START_MICROTIME) * 1000);
        }
        
        function statsd_increment($stat) 
        {
            global $CONFIG;
            
            return ElggStatsD::increment("{$CONFIG->statsd_bucket}.$stat");  
        }
        
	elgg_register_event_handler('init','system','statsd_init');
