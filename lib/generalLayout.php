<?php
require_once("permission.php");
require_once("exceptions.php");

session_start();

class GeneralLayout extends PermissionPage {

	private $elems;

	private $pages;


	public function __construct($url, $permission=null) {
		parent::__construct($permission);

		if (!$this->checkPermission()) {
			throw new UnhautorizedException();
		}
			

		$this->pages = array(
			new Page('volunteers.php','Volontari', PermissionPage::ADMIN),
			new Page('events.php', 'Gestione Eventi', PermissionPage::ADMIN),
			new Page('turns.php', 'Turni', PermissionPage::EVENING),
			new Page('mycommittments.php', 'Miei Impegni', PermissionPage::EVENING),
			new Page('eventsandcourses.php', 'Eventi', PermissionPage::AFTERNOON)
		);
		$this->elems  = array(
			'title' => '',
			'nav' => self::generateNav($this->pages, $url),
			'content' => ''
		);


	}

	

	public function yieldElem($identifier, $what) {
		$this->elems[$identifier] = $what;
	}

	public function getPage() {
		return <<<HTML
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>{$this->elems['title']}</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="lib/myStyle.css">


	</head>

	<body>
		<nav class="navbar navbar-default">
			{$this->elems['nav']}
		</nav>

		<div class="container">
			{$this->elems['content']}
		</div>

	</body>

</html>
HTML;
	}





	private static function generateNav($pages, $current_url) {
		$li='';
		foreach ($pages as $page) {
			if ( isset( $_SESSION['permessi'] ) && $_SESSION['permessi']!=0 ) {
				if ( $_SESSION['permessi'] <= $page->permessi ) {
					if ($current_url==$page->url) $active="active";
					else $active='';
					$li.="<li class='$active'><a href=\"".$page->url."\">".$page->title."</a></li>\n";
				}
			}
		}
		return <<<END
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<div class="navbar-header">
						<a class="navbar-brand" href="home.php">Lilt Volontari</a>
	    			</div>
	    			$li
				</ul>
			</div>
END;
	}

}










class Page {
	public $url;
	public $title;
	public $permessi;

	public function __construct($u,$t,$p) {
		$this->url = $u;
		$this->title = $t;
		$this->permessi = $p;
	}

}



?>