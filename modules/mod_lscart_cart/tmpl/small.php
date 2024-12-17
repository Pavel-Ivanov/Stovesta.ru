<?php
defined('_JEXEC') or die();
?>
<a href="<?= JRoute::_('cart') ?>" rel="nofollow">
<!--	<img src="/media/b0/images/cart/shopping-cart-64.png" width="32" height="32" alt=""/>-->
    <i class="uk-icon-hover uk-icon-cart-plus uk-icon-medium"></i>
	<span class="badge" id="cart-count-small" style="font-size: 18px;"><?= $cart_count;?></span>
</a>
