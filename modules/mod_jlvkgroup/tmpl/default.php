<?php
JFactory::getDocument()->addCustomTag('<script src="//vk.com/js/api/openapi.js?167"></script>');
if ($link==0){
	$linknone = 'display:none;';
	}
?>

<div  id="jlvkgroup<?= $group_id;?>"></div>
<script>
VK.Widgets.Group("jlvkgroup<?= $group_id;?>", {
    mode: <?= $mode;?>,
    wide: <?= $wide;?>,
    no_cover: <?= $cover;?>,
    width: "<?= $width;?>",
    height: "<?= $height;?>",
    color1: '<?= $color1;?>',
    color2: '<?= $color2;?>',
    color3: '<?= $color3;?>'
    },
    <?= $group_id;?>
);
</script>
