<?

require_once('../Toasty.php');
require_once('StandardWidgets.php');

$toasty = new Toasty(array('widget_class' => 'StandardWidgets', 'aggregate_blocks' => true));

$toasty->pageTitle = 'Sample Page Title';
$toasty->tabs = array('index.php?template=example1' => 'Example 1', 'index.php?template=example2' => 'Example 2','index.php?template=example3' => 'Example 3','index.php?template=example4' => 'Example 4');

$toasty->setRootDirectory('templates/');
$toasty->render('index', null, true, true, true, true);