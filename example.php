<?php

// load dibi and Profiler class from where you have them
require_once('lib/dibi.php');
require_once('lib/CodeIgniterDibiProfiler.php');

// connect to dibi with some $options set
dibi::connect($options);

// set the Profiler
$profiler = new CodeIgniterDibiProfiler;
dibi::getConnection()->setProfiler($profiler);


// from anywhere in some of your CI Controllers, turn on the profiler
$this->output->enable_profiler(TRUE);