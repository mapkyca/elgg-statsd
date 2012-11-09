<?php
	/**
	 * StatsD for Elgg
	 * Provides detailed performance information about Elgg via a Node.JS statsd server.
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
            
            // Work out default bucket
            if (!$CONFIG->statsd_bucket)
                $CONFIG->statsd_bucket = elgg_get_plugin_setting('bucket', 'elgg-statsd');
            if (!$CONFIG->statsd_bucket) {
                $CONFIG->statsd_bucket = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $CONFIG->name));
            }
            
            // Listen to hooks and events
            if (elgg_get_plugin_setting('log_hooks', 'elgg-statsd')!='no')
                elgg_register_plugin_hook_handler('all', 'all', 'statsd_log_hook_triggered', 1);
            if (elgg_get_plugin_setting('log_events', 'elgg-statsd')!='no')
                elgg_register_event_handler('all', 'all', 'statsd_log_event_triggered', 1);
	}
	
        function statsd_log_hook_triggered($hook, $entity_type, $returnvalue, $params)
        {
            // Log hooks
            ElggStatsD::increment("{$CONFIG->statsd}.hooks.$hook.$entity_type");
            
        }
        
        function statsd_log_event_triggered($event, $type, $object) {
            // Log Events
            $subtype = "";
            if (($object) && (elgg_instanceof($object))) {
                $subtype = get_subtype_from_id($object->subtype);
                $subtype = ".$subtype";
            }
            
            ElggStatsD::increment("{$CONFIG->statsd}.events.$type.$entity_type{$subtype}");
        }
	
	elgg_register_event_handler('init','system','statsd_init');
