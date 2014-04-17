<?php
require_once('bin_init.inc.php');
require_once('lib/dc_db_' . DC_DB_DRIVER . '.fnc.php');
require_once('lib/dc_archive.class.php');
require_once('lib/dc_dossier.class.php');
require_once('lib/dc_query.fnc.php');

$help = <<<EOT

  DC Display Info

  displays info about docid from piped query

  Usage: php dc_display_info.php [ OPTIONS ] <archive~ID> [ <archive~ID> ... ]

  To read the list of documents to update from STDIN, specify "-".

  Parameter list:

    -d, --startdate	<YYYYmmdd>		a startdate to use in the query.
                             		Default: today.

    -o, --output	<file>    	the file name to place the formated output.
    								Default: '/tmp/keyword.csv'

    -a  --archive	<archive>		the archive to run the query agains
        							Default: 'textos'

        --app <DC application>   	Application name.
    								Default: "default"

    -h, --help               		Display this help message.

  Copyright 2003-2006 by Digital Collections Verlagsgesellschaft mbH.
  Report bugs to: <devsup@digicol.de>

EOT;

// Parse command line options
$config = array(
    array('d, startdate', 1),
    array('o, output', 1),
    array('a, archive', 1),
    array('h, help', 0)
);

$options = DC_Cli_Getopt($argv, $config);

if (isset($options['h'])) {
    fwrite(STDOUT, $help);
    exit(0);
}

if (count($options['_']) == 0) {
    fwrite(STDERR, "Nothing to do. Try -h for more information.\n");
    exit(1);
}

$dateStamp = date("Ymd");
$outFile = '/tmp/keyword.csv';
$archive = 'textos';

if (isset($options['d'])) {
    $dateStamp = $options['d'];
}

if (isset($options['o'])) {
    if ($options[ 'o' ] == '-') {
        $fd = STDOUT;
    }
    $outFile = $options['o'];
}

if (isset($options['a'])) {
    $archive = $options['a'];
}

// Execute jobs
$i = 0;
$updated = 0;
$doc =& $app->create('DC_Dossier');
$docIds = array();

//collect all input IDS
if ($options['_'][0] == '-') { // Read from STDIN
    while (!feof(STDIN)) {
        $line = trim(fgets(STDIN));
        if ($line != '') {
            $docIds[] = $line;
        }
    }
} else {
    foreach ($options['_'] as $objectId) {
        $docIds[] = $objectId;
    }
}

foreach($docIds as $key => $val) {
    if (strpos($val, '~') === false) {
        $idNum = $val;
    } else {
        list($arch, $idNum) = explode('~', $val);
    }
    $docIds[$key] = $idNum;
}

if (! empty($arch)) {
    $archive = $arch;
}

DCCreateDisplay($docIds);

// Display summary
fwrite(STDOUT, sprintf(" ... updated %d of %d dossiers.\n\n", $updated, $i));

// Functions
function DCCreateDisplay($docIds)
{
    global $options, $dateStamp, $outFile, $archive;

    $fromTable = 'dcdesc_'.$archive;
    $joinTable1 = 'dcdatatodesc_'.$archive;
    $joinTable2 = 'dcdata_'.$archive;
    $ids = implode(",", $docIds);

    // SELECT dat.dcdata_datestamp, dsc.dcdesc_desc, dsc.dcdesc_value FROM dcdesc_1 dsc JOIN dcdatatodesc_1 j ON dsc.dcdesc_id = j.dcdtod_dcdesc_id JOIN dcdata_1 dat ON j.dcdtod_dcdata_id = dat.dcdata_id WHERE dsc.DCDESC_DESC in ('CITY ', 'IDENTITY', 'LOCAL', 'COUNTRYNAME', 'KEYWORD', 'STATE') AND dat.dcdata_datestamp = 20140417 AND and dat.dcdata_id in (pdf,-)

    $sql = 'SELECT dat.dcdata_datestamp, dsc.dcdesc_desc, dsc.dcdesc_value FROM ' . $fromTable . ' dsc JOIN ' . $joinTable1 . ' j ON dsc.dcdesc_id = j.dcdtod_dcdesc_id JOIN ' . $joinTable2 . ' dat ON j.dcdtod_dcdata_id = dat.dcdata_id WHERE dsc.DCDESC_DESC in (\'CITY \', \'IDENTITY\', \'LOCAL\', \'COUNTRYNAME\', \'KEYWORD\', \'STATE\') AND dat.dcdata_datestamp = ' . $dateStamp . ' AND dat.dcdata_id in (' . $ids . ')';

    $curs = DC_DB_Open($sql);
    if ($curs < 0) {
        fwrite(STDERR, sprintf("Error executing SQL query '%s'.\n", $sql));
        exit(1);
    }

    $fd = fopen($outFile, 'w');

    if (!$fd) {
        fwrite(STDERR, sprintf("Unable to write output file <%s>.\n", $outFile));
        exit(1);
    }

    fputcsv($fd, array("Date", "Tag", "Value"));

    while (DC_DB_Fetch($curs, $row) >= 0) {
        $fields = array();
        foreach ($row as $column => $value) {
            $fields[] = $value;
        }
        //sprintf ('% 8d, %13.13s, %208.208s', $fields[0], $fields[1], $fields[2]);
        fputcsv($fd, $fields);
    }

    fclose($fd);
}
