<?php
        global $CONFIG;
    
        $pluginenabled = $vars['entity']->pluginenabled; if(!$pluginenabled) $pluginenabled="no";
        
	$host = $vars['entity']->host;
	$port = $vars['entity']->port;
        $bucket = $vars['entity']->bucket;
        if (!$bucket) $bucket = $CONFIG->statsd_bucket;
        
        $log_users = $vars['entity']->log_users; if (!$log_users) $log_users = 'yes';
        $log_loggedoutusers = $vars['entity']->log_loggedoutusers; if (!$log_loggedoutusers) $log_loggedoutusers = 'yes';
        
        $log_hooks = $vars['entity']->log_hooks; if (!$log_hooks) $log_hooks = 'yes';
        $log_events = $vars['entity']->log_events; if (!$log_events) $log_events = 'yes';
        $log_messages = $vars['entity']->log_messages; if (!$log_messages) $log_messages = 'yes';
        $log_database = $vars['entity']->log_database; if (!$log_database) $log_database = 'yes';
        
        $log_exceptions = $vars['entity']->log_exceptions; if (!$log_exceptions) $log_exceptions = 'yes';
        $log_errors = $vars['entity']->log_errors; if (!$log_errors) $log_errors = 'yes';
        $log_warnings = $vars['entity']->log_warnings; if (!$log_warnings) $log_warnings = 'no';
        $log_notices = $vars['entity']->log_notices; if (!$log_notices) $log_notices = 'no';
        
        $log_time = $vars['entity']->log_time; if (!$log_time) $log_time = 'yes';
        
?>
<div class="section basic">
    
    <p><?php echo elgg_echo('elgg-statsd:enabled'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[pluginenabled]', 'value' => $pluginenabled, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>
    
    <p><?php echo elgg_echo('elgg-statsd:bucket'); ?>:
            <?php echo elgg_view('input/text', array('internalname' => 'params[bucket]', 'value' => $bucket)); ?>
    </p>
    <p><?php echo elgg_echo('elgg-statsd:host'); ?>:
            <?php echo elgg_view('input/text', array('internalname' => 'params[host]', 'value' => $host)); ?>
    </p>
    <p><?php echo elgg_echo('elgg-statsd:port'); ?>:
            <?php echo elgg_view('input/text', array('internalname' => 'params[port]', 'value' => $port)); ?>
    </p>
</div>

<div class="section users">
    <p><?php echo elgg_echo('elgg-statsd:log_users'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_users]', 'value' => $log_users, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>

    <p><?php echo elgg_echo('elgg-statsd:log_loggedoutusers'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_loggedoutusers]', 'value' => $log_loggedoutusers, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>
</div>


<div class="section elgg">
    <p><?php echo elgg_echo('elgg-statsd:log_events'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_events]', 'value' => $log_events, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>

    <p><?php echo elgg_echo('elgg-statsd:log_hooks'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_hooks]', 'value' => $log_hooks, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>
    
    <p><?php echo elgg_echo('elgg-statsd:log_messages'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_messages]', 'value' => $log_messages, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>
    
    <p><?php echo elgg_echo('elgg-statsd:log_database'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_database]', 'value' => $log_database, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>
</div>

<div class="section php">
    
    <p><?php echo elgg_echo('elgg-statsd:log_time'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_time]', 'value' => $log_time, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>
    
    <p><?php echo elgg_echo('elgg-statsd:log_exceptions'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_exceptions]', 'value' => $log_exceptions, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>

    <p><?php echo elgg_echo('elgg-statsd:log_errors'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_errors]', 'value' => $log_errors, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>

    <p><?php echo elgg_echo('elgg-statsd:log_warnings'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_warnings]', 'value' => $log_warnings, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>

    <p><?php echo elgg_echo('elgg-statsd:log_notices'); ?>:
        <?php echo elgg_view('input/dropdown', array('internalname' => 'params[log_notices]', 'value' => $log_notices, 'options_values' => array(
            'yes' => elgg_echo('option:yes'),
            'no' => elgg_echo('option:no'),
        ))); ?>
    </p>
</div>