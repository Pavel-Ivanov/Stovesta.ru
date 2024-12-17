<?php
defined('_JEXEC') or die();
?>
<a href="<?= JRoute::_('cart'); ?>" rel="nofollow">
	<img src="/media/b0/images/cart/shopping-cart-64.png" width="64" height="64" alt=""/>
	<span class="uk-badge uk-badge-notification" id="cart-count" style="font-size: 18px;"><?= $cart_count;?></span>
</a>