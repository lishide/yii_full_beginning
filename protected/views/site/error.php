<div class="e404">
    <img src="<?php echo Yii::app()->baseUrl; ?>/images/404.jpg" alt="">

    <p class="emsg"><?php echo $message; ?></p>
    <br>

    <p>
        <a href="<?php echo App()->request->urlReferrer ? App()->request->urlReferrer : '/'; ?>"><img
                src="<?php echo Yii::app()->baseUrl; ?>/images/goto.jpg" border="0" alt=""></a>
    </p>
</div>