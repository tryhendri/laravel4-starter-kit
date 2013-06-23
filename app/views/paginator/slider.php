<?php
	$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);

	$from = $paginator->getFrom();
	$to = $paginator->getTo();
	$total = $paginator->getTotal();
?>

<div class="pagination">
	<span class="pull left">
		<?php echo trans('pagination.showing', compact('from', 'to', 'total')); ?>
	</span>

	<ul class="pull-right">
		<?php echo $presenter->render(); ?>
	</ul>
</div>
