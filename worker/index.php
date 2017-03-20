<?PHP

require_once 'class/Worker.php';
require_once 'class/system_config.php';

echo "Worker Inited...";

$GLOBALS['sistem_config'] = new dumbu\cls\system_config();

$Worker = new dumbu\cls\Worker();

$Worker->delete_daily_work();
$Worker->prepare_daily_work();
$Worker->do_work();
?>