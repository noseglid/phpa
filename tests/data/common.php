<?php

$data =
  array(
    /* $data[0] */
    array(
      'files' => array('f.ext'),
      'units' =>
        array(
          array(
            'fnc'        => 'a',
            'file'       => 'some/path/f.ext',
            'row'        => '10',
            'complexity' => '2',
            'dependency' => '0/0',
            'sloc'       => '20'
        )
      )
    ),
    /* data[1] */
    array(
      'files' => array('a', 'b', 'c'),
      'units' =>
        array(
          array(
            'fnc'        => 'a',
            'file'       => 'path/a',
            'row'        => '10',
            'complexity' => '2',
            'dependency' => '0/0',
            'sloc'       => '20',
            'src_strip'  => 'function a() { m() }'
          ),
          array(
            'fnc'        => 'b',
            'file'       => 'path/a',
            'row'        => '20',
            'complexity' => '2',
            'dependency' => '0/0',
            'sloc'       => '20',
            'src_strip'  => 'function b() { a(); }'
          ),
          array(
            'fnc'        => 'c',
            'file'       => 'path/b',
            'row'        => '10',
            'complexity' => '2',
            'dependency' => '0/0',
            'sloc'       => '20',
            'src_strip'  => 'function c() { a(); b(); c(); }'
          ),
          array(
            'fnc'        => 'd',
            'file'       => 'path/b',
            'row'        => '22',
            'complexity' => '2',
            'dependency' => '0/0',
            'sloc'       => '20',
            'src_strip'  => "function d() { fopen($f);\na();\nb();\n}"
          ),
          array(
            'fnc'        => 'e',
            'file'       => 'path/c',
            'row'        => '10',
            'complexity' => '2',
            'dependency' => '0/0',
            'sloc'       => '20',
            'src_strip'  => 'function e() {\$fh = fopen(\$f); a(); fclose(\$fh);}'
          ),
          array(
            'fnc'        => 'f',
            'file'       => 'path/c',
            'row'        => '10',
            'complexity' => '2',
            'dependency' => '0/0',
            'sloc'       => '20',
            'src_strip'  => 'function f() { fileperms($f); c(); b();' .
                            'curl_exec($some $params); e(); }'
          )
        )
      ),
      /* $data[2] */
      array(
        array(
          array(
            'fnc'  => 'a',
            'file' => 'vfs://file.php',
            'row'  => 3,
            'err'  => 0)
          ),
        "<?php\n\nfunction a(\$par) {\necho 'hw';\n}\n?>",
        array(
          'src'       => "function a(\$par) {\necho 'hw';\n}\n",
          'src_strip' => "function a(\$par) {\necho SINGLE_QUOTE;\n}\n",
          'sloc'      => 3,
          'err'       => 0,
        )
      ),
      /* $data[3] */
      array(
        array(
          array (
            'fnc'  => 'a',
            'file' => 'vfs://file.php',
            'row'  => 2,
            'err'  => 0)
          ),
          "<?php\nfunction a(\$par) {\n/*\nsome\nml\ncomment\n*/\necho 'hw';\n}\n\n?>",
        array(
          'src'       => "function a(\$par) {\n/*\nsome\nml\ncomment\n*/\necho 'hw';\n}\n",
          'src_strip' => "function a(\$par) {\nMULI_COMMENT\necho SINGLE_QUOTE;\n}\n",
          'sloc'      => 8,
          'err'       => 0,
        )
      ),
      /* $data[4] */
      array(
        array(
          array(
            'fnc'  => 'a',
            'file' => 'vfs://file.php',
            'row'  => 1)
          ),
          "function a(\$par) {\n/*\nsome\nml\ncomment\n*/\necho 'hw';\n\n\n",
        array(
          'src'       => "Unable to parse source code for unit",
          'src_strip' => false,
          'sloc'      => -1,
          'err'       => 1,
        )
      ),
      /* $data[5] */
      array(
        /* in test: $data */
        array(
          'files' => array(
            'a.php', 'b.php'
          )
        ),
        /* file contents */
        array(
          "<?php\n\n function a() { } \n\n function bfnc_1(\$var) {\n  a();\n}\n?>",
          "<?php\n\n/*\nfunction bad_func() {}\n*/\nfunction good() {}\n?>"
        ),
        /* expected: $data */
        array(
          'files' => array(
            'vfs://a.php', 'vfs://b.php'
          ),
          'units' => array(
            array(
              'fnc'  => 'a',
              'file' => 'vfs://a.php',
              'row'  => 3,
              'err'  => 0
            ),
            array(
              'fnc'  => 'bfnc_1',
              'file' => 'vfs://a.php',
              'row'  => 5,
              'err'  => 0
            ),
            array(
              'fnc'  => 'good',
              'file' => 'vfs://b.php',
              'row'  => 6,
              'err'  => 0
            )
          )
        )
      ),
      /* data[6] */
      array(
        array('units' =>
          array(
            array(
              'fnc'  => 'a',
              'file' => 'vfs://file.php',
              'row'  => 2,
              'err'  => 0),
            array(
              'fnc'  => 'b',
              'file' => 'vfs://file.php',
              'row'  => 4,
              'err'  => 0),
            array(
              'fnc'  => 'a',
              'file' => 'vfs://file.php',
              'row'  => 6,
              'err'  => 0,
            )
          )
        ),
        array('units' =>
          array(
            array(
              'fnc'  => 'a',
              'file' => 'vfs://file.php',
              'row'  => 2,
              'err'  => 0,
              'wrn'  => 1),
            array(
              'fnc'  => 'b',
              'file' => 'vfs://file.php',
              'row'  => 4,
              'err'  => 0,
              'wrn'  => 0),
            array(
              'fnc'  => 'a',
              'file' => 'vfs://file.php',
              'row'  => 6,
              'err'  => 0,
              'wrn'  => 1)
          )
        )
      )
    );

