StatsD plugin for Elgg
======================

StatsD is a Node.JS stats server created by the people at etsy to provide a simple way of logging useful statistics from software.

These statistics are an invaluable way of monitoring the performance of your application, monitoring the performance of software
changes and diagnosing faults.

This is an Elgg plugin for interfacing your application to a StatsD server.

What this plugin does
---------------------
This plugin gives you an overview of what is happening in your Elgg install by logging important system level things - events, hook triggers, errors, exceptions etc.

This lets you get a very clear idea of how your Elgg network is performing, and quickly see the effect that changes have on your users.

Installation
------------
 * Install Node.JS, either from git-hub (https://github.com/joyent/node) or the package manager for your OS
 * Install StatsD, available from https://github.com/etsy/statsd
 * Not required, but highly recommended, install a Graphite server for graph visualisation (http://graphite.wikidot.com/start)
 * Install Elgg 
 * Place this plugin in mod/elgg-statsd and activate it via your admin panel.
 * Visit the plugin configuration page and set up the appropriate options

If all the backend and infrastructure stuff has been set up correctly you should now be recording numerous performance statistics about your elgg installation.

See
---
 * Author: Marcus Povey <http://www.marcus-povey.co.uk> (specifically this blog post: http://www.marcus-povey.co.uk/2012/11/19/profiling-elgg-site-performance-with-statsd-and-node-js/)
 * Node.JS <https://github.com/joyent/node>
 * StatsD <https://github.com/etsy/statsd>
 * Graphite Graphing Server <http://graphite.wikidot.com/start>
