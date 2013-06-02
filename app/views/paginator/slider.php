<?php
	$presenter = new Presenters\Paginator\BootstrapPresenter($paginator);
?>

<div class="pagination">
	<span class="pull left">
		Showing
		<?php echo $paginator->getFrom(); ?>
		-
		<?php echo $paginator->getTo(); ?>
		of
		<?php echo $paginator->getTotal(); ?>
		items
	</span>

	<ul class="pull-right">
		<?php echo $presenter->render(); ?>
	</ul>
</div>
