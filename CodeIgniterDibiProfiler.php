<?php

/**
 * dibi - tiny'n'smart database abstraction layer
 * ----------------------------------------------
 *
 * Copyright (c) 2005, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "dibi license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://dibiphp.com
 *
 * @copyright  Copyright (c) 2005, 2009 David Grudl
 * @license    http://dibiphp.com/license  dibi license
 * @link       http://dibiphp.com
 * @package    dibi
 */

/**
 * DiBi CodeIgniter 2 profiler to display SQL and Exceptions in CI 2 Profiler overview, with the same format as native queries profiler in CI 2
 *
 * @author     Dalibor Simacek
 * @copyright  Copyright (c) 2011 Dalibor Simacek
 */
class CodeIgniterDibiProfiler extends DibiObject implements IDibiProfiler
{

	private $CI;

	/** @var array */
	public $tickets = array();
	/** @var string */
	private $html_string = '';

	public function __construct()
	{
		$this->CI = & get_instance();
	}

	/**
	 * Before event notification.
	 * @param  DibiConnection
	 * @param  int     event name
	 * @param  string  sql
	 * @return int
	 */
	public function before(DibiConnection $connection, $event, $sql = NULL)
	{
		$this->tickets[] = array($connection, $event, $sql);
		end($this->tickets);
		return key($this->tickets);
	}

	/**
	 * After event notification.
	 * @param  int
	 * @param  DibiResult
	 * @return void
	 */
	public function after($ticket, $res = NULL)
	{
		if (!isset($this->tickets[$ticket]))
		{
			throw new InvalidArgumentException('Bad ticket number.');
		}

		list($connection, $event, $sql) = $this->tickets[$ticket];
		$sql = trim($sql);

		if ($event & self::QUERY)
		{
			try
			{
				$count = $res instanceof DibiResult ? count($res) : '-';
			}
			catch (Exception $e)
			{
				$count = '?';
			}

			$this->writeHtmlSql(dibi::$elapsedTime, $sql, $count);
		}
	}

	private function writeHtmlSql($time, $sql, $count)
	{
		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

		$sql = highlight_code($sql, ENT_QUOTES);

		foreach ($highlight as $bold)
		{
			$sql = str_replace($bold, '<strong>' . $bold . '</strong>', $sql);
		}

		$this->html_string .= "<tr>".
			"<td style='padding:5px; vertical-align: top;width:1%;color:#900;font-weight:normal;background-color:#ddd;'>" . sprintf('%0.3f', $time * 1000) . "ms<br>".$count." rows</td>".
			"<td style='padding:5px; color:#000;font-weight:normal;background-color:#ddd;'>" . $sql . "</td></tr>\n";
	}

	public function getHtml()
	{
		return $this->html_string;
	}

	/**
	 * After exception notification.
	 * @param  DibiDriverException
	 * @return void
	 */
	public function exception(DibiDriverException $exception)
	{
		$message = $exception->getMessage();
		$code = $exception->getCode();
		if ($code)
		{
			$message = "[$code] $message";
		}

		$this->html_string .= "<tr>".
			"<td style='padding:5px; vertical-align: top;width:1%;color:#900;font-weight:normal;background-color:#ddd;'><string>ERROR</strong></td>".
			"<td style='padding:5px; color:#000;font-weight:normal;background-color:#ddd;'><strong>" .$message. "</strong><br>" . dibi::$sql . "</td></tr>\n";
	}

}
