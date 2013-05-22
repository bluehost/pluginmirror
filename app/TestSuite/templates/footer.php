<?php
/**
 * Footer for custom HTML PHPUnit reporter.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.TestSuite
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>

<?php
	App::uses('View', 'View');
	$null = null;
	$View = new View($null, false);
	echo $View->element('sql_dump');
?>

	</div><!-- .small-12 -->
</div><!-- .row -->

<div class="row">
	<div class="small-12 columns">
		<hr>
		<footer>
			<ul class="inline-list pull-right">
				<li>CakePHP <?php echo Configure::version(); ?></li>
				<li>PHPUnit <?php echo PHPUnit_Runner_Version::id(); ?></li>
			</ul>
		</footer>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="/js/foundation.min.js"></script>
<script>$(document).foundation();</script>

</body>
</html>