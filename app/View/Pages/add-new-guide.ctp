<?php
/**
 * Static Page: Add New Guide
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Pages
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 */

$this->extend('/Common/sidebar');

$title_for_layout = __('Adding a New Guide');
$this->set('title_for_layout', $title_for_layout);

?>

<h2><?php echo h($title_for_layout); ?></h2>

<p>
	We love hearing about new ways developers, designers, and administrators are
	using the Plugin Mirror repositories.  If you have found a new way to really
	take advantage of these repos, and want to let everyone else in on the fun,
	feel free to use the <a href="https://github.com/bluehost/pluginmirror/blob/master/app/View/Pages/plugin-developer-guide.ctp" target="_blank">Plugin Developer Guide</a>
	as a template for a <a href="https://github.com/bluehost/pluginmirror/new/master/app/View/Pages" target="_blank">new guide</a>
	(you must be registered and logged into GitHub).  Write up your guide,
	submit a pull request, and we'll be happy to work with you to get it posted
	on the home page.
</p>