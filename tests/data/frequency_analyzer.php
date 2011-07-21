<?php

$xdebug_trace = array(
  array(
'Version: 2.0.3
TRACE START [2009-11-05 09:55:27]
1 0 0 0.000099  97608 {main}  1   /tmp/path/a.php  0
2 1 0 0.000138  97840 a 1   /tmp/path/a.php  10
2 1 1 0.000149  97840
2 2 0 0.000156  97840 b 1   /tmp/path/a.php  12
3 3 0 0.000164  98072 a 1   /tmp/path/a.php  7
3 3 1 0.000172  98072
2 2 1 0.000178  98072
1 0 1 0.000184  98072
      0.000226  43872
TRACE END   [2009-11-05 09:55:27]',
    array('{main}' => 1,
          'a' => 2,
          'b' => 1)
  ),
  array(
'Version: 2.0.3
TRACE START [2009-11-05 12:17:41]
1 0 0 0.000148  101576  {main}  1   /tmp/a.php  0
2 1 0 0.000198  101808  a 1   /tmp/a.php  15
2 1 1 0.000215  101808
2 2 0 0.000225  101808  b 1   /tmp/a.php  17
3 3 0 0.000237  102040  a 1   /tmp/a.php  7
3 3 1 0.000249  102040
2 2 1 0.000258  102040
2 4 0 0.000300  102392  is_array  0   /tmp/a.php  19
2 4 1 0.000317  102392
2 5 0 0.000325  102392  c 1   /tmp/a.php  21
3 6 0 0.000336  102392  b 1   /tmp/a.php  11
4 7 0 0.000347  102392  a 1   /tmp/a.php  7
4 7 1 0.000359  102392
3 6 1 0.000367  102392
3 8 0 0.000375  102392  a 1   /tmp/a.php  12
3 8 1 0.000387  102392
2 5 1 0.000396  102392
1 0 1 0.000405  102392
        0.000486  45200
TRACE END   [2009-11-05 12:17:41]',
    array('{main}' => 1,
          'a' => 4,
          'b' => 2,
          'c' => 1)
  )
);

$xdebug_trace_analyze = array (
  array(
  /* $xdebug_trace_analyze[0] */
  array(
    'files' => array('a.php'),
    'xdbt'  => array('vfs://file.php'),
    'units' =>
      array(
        array(
          'fnc' => 'a',
          'file' => 'vfs://a.php',
          'row' => 3,
          'err' => 0
        ),
        array(
          'fnc' => 'b',
          'file' => 'vfs://a.php',
          'row' => 6,
          'err' => 0
        )
      )
  ),
'Version: 2.0.3
TRACE START [2009-11-05 09:55:27]
1 0 0 0.000099  97608 {main}  1   /tmp/path/a.php  0
2 1 0 0.000138  97840 a 1   /tmp/path/a.php  10
2 1 1 0.000149  97840
2 2 0 0.000156  97840 b 1   /tmp/path/a.php  12
3 3 0 0.000164  98072 a 1   /tmp/path/a.php  7
3 3 1 0.000172  98072
2 2 1 0.000178  98072
1 0 1 0.000184  98072
      0.000226  43872
TRACE END   [2009-11-05 09:55:27]',
  array(
    'files' => array('a.php'),
    'units' =>
      array(
        array(
          'fnc' => 'a',
          'file' => 'vfs://a.php',
          'row' => 3,
          'err' => 0,
          'frequency' => 2
        ),
        array(
          'fnc' => 'b',
          'file' => 'vfs://a.php',
          'row' => 6,
          'err' => 0,
          'frequency' => 1
        )
      )
    ),
  /* $xdebug_trace_analyze[1] */
  array(
    'files' => array('a.php'),
    'xdbt'  => array('vfs://file.php'),
    'units' =>
      array(
        array(
          'fnc' => 'a',
          'file' => 'vfs://a.php',
          'row' => 3,
          'err' => 0
        ),
        array(
          'fnc' => 'b',
          'file' => 'vfs://a.php',
          'row' => 6,
          'err' => 0
        ),
        array(
          'fnc' => 'c',
          'file' => 'vfs://a.php',
          'row' => 10,
          'err' => 0
        )
      )
    ),
'Version: 2.0.3
TRACE START [2009-11-05 12:17:41]
1	0	0	0.000148	101576	{main}	1		/tmp/a.php	0
2	1	0	0.000198	101808	a	1		/tmp/a.php	15
2	1	1	0.000215	101808
2	2	0	0.000225	101808	b	1		/tmp/a.php	17
3	3	0	0.000237	102040	a	1		/tmp/a.php	7
3	3	1	0.000249	102040
2	2	1	0.000258	102040
2	4	0	0.000300	102392	is_array	0		/tmp/a.php	19
2	4	1	0.000317	102392
2	5	0	0.000325	102392	c	1		/tmp/a.php	21
3	6	0	0.000336	102392	b	1		/tmp/a.php	11
4	7	0	0.000347	102392	a	1		/tmp/a.php	7
4	7	1	0.000359	102392
3	6	1	0.000367	102392
3	8	0	0.000375	102392	a	1		/tmp/a.php	12
3	8	1	0.000387	102392
2	5	1	0.000396	102392
1	0	1	0.000405	102392
			0.000486	45200
TRACE END   [2009-11-05 12:17:41]',
  array(
    'files' => array('a.php'),
    'units' =>
      array(
        'fnc' => 'a',
        'file' => 'vfs://a.php',
        'row' => 3,
        'err' => 0,
        'frequency' => 4
      ),
      array(
        'fnc' => 'b',
        'file' => 'vfs://a.php',
        'row' => 6,
        'err' => 0,
        'frequency' => 2
      ),
      array(
        'fnc' => 'c',
        'file' => 'vfs://a.php',
        'row' => 10,
        'err' => 0
      )
    )
  )
);

