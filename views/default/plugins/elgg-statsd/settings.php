<?php

	$host = $vars['entity']->host;
	$port = $vars['entity']->port;
        $bucket = $vars['entity']->bucket;
        
        $log_hooks = $vars['entity']->log_hooks; if (!$log_hooks) $log_hooks = 'yes';
        $log_events = $vars['entity']->log_events; if (!$log_events) $log_events = 'yes';
        
        $log_exceptions = $vars['entity']->log_exceptions; if (!$log_exceptions) $log_exceptions = 'yes';
        $log_errors = $vars['entity']->log_errors; if (!$log_errors) $log_errors = 'yes';
        $log_warnings = $vars['entity']->log_warnings; if (!$log_warnings) $log_warnings = 'yes';
        $log_notices = $vars['entity']->log_notices; if (!$log_notices) $log_notices = 'no';
        
        
?>
<p><?php echo elgg_echo('elgg-statsd:bucket'); ?>:
	<?php echo elgg_view('input/text', array('internalname' => 'params[bucket]', 'value' => $bucket)); ?>
</p>
<p><?php echo elgg_echo('elgg-statsd:host'); ?>:
	<?php echo elgg_view('input/text', array('internalname' => 'params[host]', 'value' => $host)); ?>
</p>
<p><?php echo elgg_echo('elgg-statsd:port'); ?>:
	<?php echo elgg_view('input/number', array('internalname' => 'params[port]', 'value' => $port)); ?>
</p>

<br /><br />

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
