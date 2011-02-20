DiBi custom profiler for CodeIgniter 2
======================================

Included CodeIgniter custom Profiler class and custom Profiler for [Dibi](http://dibiphp.com/) will allow you to see SQL queries and Exceptions ran through dibi database layer in
[CodeIgniter Profiler](http://codeigniter.com/user_guide/general/profiling.html) output.

It has a same code highlighting as native CI queries overview.

1. put MY_Profiler.php into your CI application_folder/libraries
2. turn on CI Profiler output in some Controller with $this->output->enable_profiler(TRUE);
3. assign CodeIgniterDibiProfiler to Dibi as a Profiler with $profiler = new CodeIgniterDibiProfiler; dibi::getConnection()->setProfiler($profiler);

Then you'll see this section in CI Profiler output:

![Output screenshot](http://awesomescreenshot.com/0de7wce6d)