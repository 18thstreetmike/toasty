<?

ini_set('display_errors', 'On');

require_once('../Toasty.php');
require_once('StandardWidgets.php');

$toasty = new Toasty(array('widget_class' => 'StandardWidgets', 'aggregate_blocks' => true));

// set this to a directory that the webserver can write to that is under the web root
$toasty->setWorkingDirectory('/toasty/examples/temp/');

// comment this out if you want to have inline css and js
$toasty->setCreateFiles(true);

$toasty->pageTitle = 'Sample Page Title';
$toasty->tabs = array('index.php?template=example1' => 'Example 1', 'index.php?template=example2' => 'Example 2','index.php?template=example3' => 'Example 3','index.php?template=example4' => 'Example 4');
$toasty->subnavtabs = array('index.php?template=example1' => 'Example 1', 'index.php?template=example2' => 'Example 2','index.php?template=example3' => 'Example 3','index.php?template=example4' => 'Example 4');

$toasty->setRootDirectory('templates/');
$toasty->render('index', null, true, true, true, true);