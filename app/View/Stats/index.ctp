<?php
/**
 * Stats index view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Stats
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 * @var string $title_for_layout
 * @var array $latest_stat
 */

$this->start('css');
echo $this->Html->css('rickshaw.min');
?>
<style type="text/css">
	.rickshaw_legend {
		display: block;
		padding: 0 0 20px;
		background: none;
		color: black;
		font-size: 14px;
		font-family: "Open Sans", Calibri, Candara, Arial, sans-serif;
	}
	.rickshaw_legend ul {
		margin: 0;
	}
	.rickshaw_legend .line {
		line-height: 20px;
	}
	.rickshaw_graph .detail_swatch {
		float: right;
		display: inline-block;
		width: 10px;
		height: 10px;
		margin: 0 4px 0 0
	}
	.rickshaw_graph .detail .x_label {
		display: none;
	}
	.rickshaw_graph .detail .item {
		padding: .25em .50em;
	}
</style>
<?php
$this->end();

$this->start('script');
echo $this->Html->script(
	array(
		'//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js',
		'd3.v2.min', 'rickshaw.min'
	)
);
?>
<script>
	var time = new Rickshaw.Fixtures.Time();
	var palette = new Rickshaw.Color.Palette();

	var graph = new Rickshaw.Graph.Ajax({
		element: document.querySelector("#plugin_stats"),
		height: 300,
		padding: { top: 0.05 },
		renderer: 'area',
		dataURL: '/stats.json',
		onData: function(data) {
			var result = [ {
				name: 'Updating',
				color: 'rgba(2, 162, 203, 1)',
				data: []
			}, {
				name: 'Cloning',
				color: 'rgba(235, 176, 53, 1)',
				data: []
			}, {
				name: 'Refreshing',
				color: 'rgba(215, 249, 34, 1)',
				data: []
			} ];
			for(var i = 0; i < data.length; i++) {
				var date = Date.parse(data[i][6]) / 1000;
				result[0].data.push({ x: date, y: data[i][0] });
				result[1].data.push({ x: date, y: data[i][1] });
				result[2].data.push({ x: date, y: data[i][2] });
			}
			return result;
		},
		onComplete: function(t) {
			t.graph.render();

			var plugin_x_axis = new Rickshaw.Graph.Axis.Time({
				graph: t.graph,
				ticksTreatment: 'glow',
				pixelsPerTick: 40
			});
			plugin_x_axis.render();
			var plugin_y_axis = new Rickshaw.Graph.Axis.Y({
				graph: t.graph,
				tickFormat: Rickshaw.Fixtures.Number.formatKMBT,
				ticksTreatment: 'glow',
				pixelsPerTick: 40
			});
			plugin_y_axis.render();

			new Rickshaw.Graph.HoverDetail({
				graph: t.graph,
				formatter: function(series, x, y) {
					var date = '<span class="date">' + new Date(x * 1000).toLocaleString() + '</span>';
					return series.name + ": " + parseInt(y) + '<br/>' + date;
				}
			});

			var plugin_legend = new Rickshaw.Graph.Legend({
				graph: t.graph,
				element: document.querySelector('#plugin_legend')
			});
			new Rickshaw.Graph.Behavior.Series.Toggle({
				graph: t.graph, legend: plugin_legend
			});
			new Rickshaw.Graph.Behavior.Series.Highlight({
				graph: t.graph, legend: plugin_legend
			});
			new Rickshaw.Graph.Behavior.Series.Order({
				graph: t.graph, legend: plugin_legend
			});
		}
	});
</script>
<?php
$this->end();

unset($latest_stat['Stat']['id']);
unset($latest_stat['Stat']['created']);
unset($latest_stat['Stat']['created_iso8601']);
$headers = array_map(array('Inflector', 'humanize'), array_keys($latest_stat['Stat']));
$values = array_map('number_format', array_values($latest_stat['Stat']));

?>
<div class="row">
	<div class="small-12 columns">
		<h2><?php echo $title_for_layout; ?></h2>
		<table>
			<thead>
				<?php echo $this->Html->tableHeaders($headers); ?>
			</thead>
			<tbody>
				<?php echo $this->Html->tableCells($values); ?>
			</tbody>
		</table>
		<br/>
		<div class="row">
			<div class="large-2 columns">
				<div id="plugin_legend"></div>
			</div>
			<div class="large-10 columns">
				<div id="plugin_stats"></div>
			</div>
		</div>
		<h3><?php echo __('Description'); ?></h3>
		<ul>
			<li><b>Total:</b> Number of plugins in the WordPress.org SVN repository.</li>
			<li><b>Cloned:</b> Number of plugins cloned up to GitHub.</li>
			<li><b>Removed:</b> Plugins not listed on WordPress.org (these can still be cloned).</li>
			<li><b>Refreshing:</b> Plugins whose information needs to be updated from the WordPress.org Plugin API.</li>
			<li><b>Cloning:</b> Plugins requested to be cloned that have not been processed yet.</li>
			<li><b>Updating:</b> Number of cloned plugins with changes in SVN that still need to be pushed up to GitHub.</li>
		</ul>
	</div>
</div>