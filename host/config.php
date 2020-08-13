<?php

$GLOBALS['fdlconfig'] =
    [
    'mysql' => [
        'server' => "project131.federation.edu.au",
        'dbname' => "fdlgrades",
        'username' => "user",
        'password' => 'DiSN2$zujQxhF2U6I',
    ],
    'ldap' => [
        'username' => 'cn=30346495,ou=students,dc=uni,dc=federation,dc=edu,dc=au', // insert your student ID in here
        'password' => 's.28041999',
    ],
    'photos' => [
        'hashkey' => '???',
    ],
    'csdb' => [
        'username' => "fdlgrades",
        'password' => '?????',
        'connstr' => '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=eakala-scan.federation.edu.au)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=csprod.federation.edu.au)(SERVER=DEDICATED)))',
    ],
    'testing' => [
        'auto_login' => false,
    ],
];
