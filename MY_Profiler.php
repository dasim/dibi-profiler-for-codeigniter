<?php

class MY_Profiler extends CI_Profiler
{

	protected $_available_sections = array(
		'benchmarks',
		'get',
		'memory_usage',
		'post',
		'uri_string',
		'controller_info',
		'queries',
		'dibi',
		'http_headers',
		'config',
	);

	// --------------------------------------------------------------------

	/**
	 * Compile Queries
	 *
	 * @return	string
	 */
	protected function _compile_dibi()
	{
		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

		$output = "\n\n";

		$output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
		$output .= "\n";
		$output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;DiBi log ('.dibi::$numOfQueries.' queries, time: '.sprintf('%0.3f',dibi::$totalTime).'s) &nbsp;&nbsp;&nbsp;</legend>';
		$output .= "\n";
		$output .= "\n\n<table style='width:100%;'>\n";

		$output .= dibi::getProfiler()->getHtml();

		$output .= "</table>\n";
		$output .= "</fieldset>";

		return $output;
	}

}