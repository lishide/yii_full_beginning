 <?php if( Yii::app()->user->hasFlash('RightsSuccess')===true || Yii::app()->user->hasFlash('RightsError')===true ):?>

 <div class="not_content_mini">

	<?php if( Yii::app()->user->hasFlash('RightsSuccess')===true ):?>

	    <div class="tips_success">

	        <?php echo Yii::app()->user->getFlash('RightsSuccess'); ?>

	    </div>

	<?php endif; ?>

	<?php if( Yii::app()->user->hasFlash('RightsError')===true ):?>

	    <div class="tips_error">

	        <?php echo Yii::app()->user->getFlash('RightsError'); ?>

	    </div>

	<?php endif; ?>

 </div>

 <?php
 Yii::app()->clientScript->registerScript('flash', "
	$('.not_content_mini').animate({opacity: 0}, 3000).slideUp('slow');
 ");

 endif;
 ?>