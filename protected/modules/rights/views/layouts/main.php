<?php $this->beginContent(Rights::module()->appLayout); ?>

<div  class="container">

	<div id="content">


		<?php $this->renderPartial('/_flash'); ?>

		<?php echo $content; ?>

	</div><!-- content -->

</div>

<?php $this->endContent(); ?>