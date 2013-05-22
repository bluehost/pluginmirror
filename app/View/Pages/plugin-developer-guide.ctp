<?php
/**
 * Static Page: Plugin Developer Guide
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Pages
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 */

$this->extend('/Common/sidebar');

$title_for_layout = __('Plugin Developer Guide');
$this->set('title_for_layout', $title_for_layout);

?>

<h2><?php echo h($title_for_layout); ?></h2>

<p>
	These repositories are traditional <code>git-svn</code> mirrors. This means
	they can be used for making commits directly back to the WordPress.org
	Plugins Subversion repository. After rebasing any new commits on top of the
	desired branch you wish to commit to (either "master" for the SVN "trunk",
	or any other branch), performing a <code>git svn dcommit</code> will use
	your existing SVN credentials to push those commits up to the WordPress.org
	Plugins SVN repo. You will need Subversion and <code>git-svn</code>
	installed, however, you won't need a SVN checkout of your plugin or any
	"GitHub sync" scripts to do this.
</p>

<h3>Getting Started</h3>

<p>
	In order for this to work properly, you will need to tell your Git clone
	that there is a SVN repository this was cloned from and where it is. You can
	do this by running the following commands from within your Git clone, while
	replacing <code>my-plugin-slug</code> with your plugin slug:
</p>

<pre><code>git config svn-remote.svn.url https://plugins.svn.wordpress.org
git config svn-remote.svn.fetch my-plugin-slug/trunk:refs/remotes/origin/master
git config svn-remote.svn.branches my-plugin-slug/branches/*:refs/remotes/origin/*
</code></pre>

<p>
	For every branch you checkout, including the "master" branch you checked out
	when you cloned, Git will need to update the SVN metadata it stores for
	working with SVN. This takes very little time to do, and can be triggered
	simply by running:
</p>

<pre><code>git svn info</code></pre>

<p>
	This should also happen automatically when you run
	<code>git svn dcommit</code> as well, but it's nice to get it out of the
	way first to make sure you have everything configured correctly.
</p>

<h3>Working Within Git</h3>

<p>
	Since we are required to continue working with SVN on WordPress.org, there
	are some general rules and guidelines for how you must use Git in order to
	stay compatible with the SVN workflow.
</p>

<p>
	First: Remember to always rebase your new commits on top of the latest head
	commit from the upstream mirror on GitHub before pushing commits to SVN.
	You can do this in two different ways. The most efficient way is to
	perform a remote update on the upstream mirror, and rebase your local
	branch on top of the upstream remote branch. However, another way you
	could do this is to run <code>git svn rebase</code> on your local
	branch. This grabs the latest commits directly from the WordPress.org
	Plugins SVN repo, which can be very time consuming, but works the same
	way.
</p>

<p>
	Second: Do not <code>dcommit</code> Git merge commits to the Subversion
	repository. Subversion doesn't handle merges in the same way as Git, and
	this will cause problems. This means you should keep your Git
	development history linear (i.e., no merging from other branches, just
	rebasing).
</p>

<p>
	Third: Do not amend, reorder, or otherwise change commits that have been
	commited to Subversion. This is essentially the same rule as not
	changing Git commits that have been pushed to public repositories.
	Subversion cannot handle modifying or reordering commits.
</p>

<h3>Branching and Tagging</h3>

<p>
	While it's possible to create branches and tags directly on the SVN server
	using SVN clients without checking out the code, you might still find it
	easier to create them directly from within your Git repository.
</p>
