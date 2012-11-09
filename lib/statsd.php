<?php
	/**
	 * Elgg StatsD library.
	 *
	 * @licence GNU Public License version 2
	 * @link https://github.com/mapkyca/elgg-statsd
	 * @link http://www.marcus-povey.co.uk
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 */

/**
 * Sends statistics to the stats daemon over UDP
 *
 **/

class ElggStatsD {

    /**
     * Sets one or more timing values
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $time The elapsed time (ms) to log
     **/
    public static function timing($stats, $time) {
        ElggStatsD::updateStats($stats, $time, 1, 'ms');
    }

    /**
     * Sets one or more gauges to a value
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $value The value for the stats.
     **/
    public static function gauge($stats, $value) {
        ElggStatsD::updateStats($stats, $value, 1, 'g');
    }

    /**
     * A "Set" is a count of unique events.
     * This data type acts like a counter, but supports counting
     * of unique occurences of values between flushes. The backend
     * receives the number of unique events that happened since
     * the last flush.
     *
     * The reference use case involved tracking the number of active
     * and logged in users by sending the current userId of a user
     * with each request with a key of "uniques" (or similar).
     *
     * @param string|array $stats The metric(s) to set.
     * @param float $value The value for the stats.
     **/
    public static function set($stats, $value) {
        ElggStatsD::updateStats($stats, $value, 1, 's');
    }

    /**
     * Increments one or more stats counters
     *
     * @param string|array $stats The metric(s) to increment.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return boolean
     **/
    public static function increment($stats, $sampleRate=1) {
        ElggStatsD::updateStats($stats, 1, $sampleRate, 'c');
    }

    /**
     * Decrements one or more stats counters.
     *
     * @param string|array $stats The metric(s) to decrement.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @return boolean
     **/
    public static function decrement($stats, $sampleRate=1) {
        ElggStatsD::updateStats($stats, -1, $sampleRate, 'c');
    }

    /**
     * Updates one or more stats.
     *
     * @param string|array $stats The metric(s) to update. Should be either a string or array of metrics.
     * @param int|1 $delta The amount to increment/decrement each metric by.
     * @param float|1 $sampleRate the rate (0-1) for sampling.
     * @param string|c $metric The metric type ("c" for count, "ms" for timing, "g" for gauge, "s" for set)
     * @return boolean
     **/
    public static function updateStats($stats, $delta=1, $sampleRate=1, $metric='c') {
        if (!is_array($stats)) { $stats = array($stats); }
        $data = array();
        foreach($stats as $stat) {
            $data[$stat] = "$delta|$metric";
        }

        ElggStatsD::send($data, $sampleRate);
    }

    /*
     * Squirt the metrics over UDP
     **/
    public static function send($data, $sampleRate=1) {
        $config = ElggStatsDConfig::getInstance();
        if (! $config->isEnabled("statsd")) { return; }

        // sampling
        $sampledData = array();

        if ($sampleRate < 1) {
            foreach ($data as $stat => $value) {
                if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
                    $sampledData[$stat] = "$value|@$sampleRate";
                }
            }
        } else {
            $sampledData = $data;
        }

        if (empty($sampledData)) { return; }

        // Wrap this in a try/catch - failures in any of this should be silently ignored
        try {
            $host = $config->getConfig("statsd.host");
            $port = $config->getConfig("statsd.port");
            $fp = fsockopen("udp://$host", $port, $errno, $errstr);
            if (! $fp) { return; }
            foreach ($sampledData as $stat => $value) {
                fwrite($fp, "$stat:$value");
            }
            fclose($fp);
        } catch (Exception $e) {
        }
    }
}

class ElggStatsDConfig
{
    private static $_instance;
    private $_data;

    private function __construct()
    {
        $this->_data = array(
			'statsd.host' => elgg_get_plugin_setting('host', 'elgg-statsd'),
			'statsd.port' => elgg_get_plugin_setting('port', 'elgg-statsd'),
        );
    }

    public static function getInstance()
    {
        if (!self::$_instance) self::$_instance = new self();

        return self::$_instance;
    }

    public function isEnabled($section)
    {
        return isset($this->_data[$section]);
    }

    public function getConfig($name)
    {
        $name_array = explode('.', $name, 2);

        if (count($name_array) < 2) return;

        list($section, $param) = $name_array;

        if (!isset($this->_data[$section][$param])) return;

        return $this->_data[$section][$param];
    }
}

/* Config file example (put it into "statsd.ini"):

[statsd]
host = yourhost
port = 8125

*/
