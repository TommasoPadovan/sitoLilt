<?php
/**
 * Created by IntelliJ IDEA.
 * User: kurt
 * Date: 11/03/2017
 * Time: 14:19
 */

require_once('../lib/permission.php');
require_once('../lib/command.php');
require_once('../lib/sqlLib.php');

class AdminRemoveReservationCommand extends Command {

    private $lastPage;

    public function __construct($permission) {
        parent::__construct($permission);
         if (isset($_SERVER['HTTP_REFERER']))
            $this->lastPage = $_SERVER['HTTP_REFERER'];
        else $this->lastPage = 'eventsandcourses.php';
    }

    protected function template() {
        $db = new DbConnection();

        $db->deleteRows('eventsattendants', [
            'event' => $_GET['event'],
            'volunteer' => $_GET['volunteer']
        ]);

        header("Location: ".$this->lastPage);
    }
}

try {
    (new AdminRemoveReservationCommand(PermissionPage::ADMIN))->execute();
}
catch(UnhautorizedException $e) {
    $e->echoAlert();
}