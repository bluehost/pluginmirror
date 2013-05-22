<?php
/**
 * Default RSS layout.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Layouts
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 * @var string $title_for_layout
 */

if (!isset($channel)) {
	$channel = array();
}
if (!isset($channel['title'])) {
	$channel['title'] = $title_for_layout;
}

echo $this->Rss->document(
	$this->Rss->channel(
		array(), $channel, $this->fetch('content')
	)
);
