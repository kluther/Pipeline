<?php
// http://core.svn.wordpress.org/trunk/wp-includes/formatting.php
/**
 * Determines the difference between two timestamps.
 *
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "2 days".
 *
 * @since 1.5.0
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 * @return string Human readable time difference.
 */

function formatCount($n, $singular, $plural, $none = '0')
{
	if ($n == 0) {
		return "{$none}&nbsp;{$plural}";
	} elseif ($n == 1) {
		return "{$n}&nbsp;{$singular}";
	} else {
		return "{$n}&nbsp;{$plural}";
	}
}
 
function human_time_diff( $from, $to = '' ) {
	if ( empty($to) )
		$to = time();
	$diff = (int) abs($to - $from);
	if ($diff <= 3600) {
		$mins = round($diff / 60);
		if ($mins <= 1) {
			$mins = 1;
		}
		/* translators: min=minute */
		$since = formatCount($mins, 'min', 'mins');
	} else if (($diff <= 86400) && ($diff > 3600)) {
		$hours = round($diff / 3600);
		if ($hours <= 1) {
			$hours = 1;
		}
		$since = formatCount($hours, 'hour', 'hours');
	} elseif ($diff >= 86400) {
		$days = round($diff / 86400);
		if ($days <= 1) {
			$days = 1;
		}
		$since = formatCount($days, 'day', 'days');
	}
	$formatted = (($to-$from) < 0) ? ("in ".$since) : ($since." ago");
	return $formatted;
}
?>