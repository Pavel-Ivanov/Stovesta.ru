<?php
defined('_JEXEC') or die();

JImport('b0.Sparepart.SparepartIds');
JImport('b0.Accessory.AccessoryIds');
JImport('b0.Product.Product');

$doc = JFactory::getDocument();
//b0dd($doc->_scripts);
unset($doc->_generator);
//unset($doc->_scripts['/media/jui/js/jquery.min.js']);
unset($doc->_scripts['/media/jui/js/jquery-noconflict.js']);
unset($doc->_scripts['/media/jui/js/jquery-migrate.min.js']);
unset($doc->_scripts['/media/jui/js/bootstrap.min.js']);
unset($doc->_scripts['/media/system/js/mootools-core.js']);
unset($doc->_scripts['/media/system/js/mootools-more.js']);
unset($doc->_scripts['/media/system/js/core.js']);
unset($doc->_scripts['/media/system/js/modal.js']);
//unset($doc->_scripts['/component/cobalt/?task=ajax.mainJS&Itemid=1']);
//unset($doc->_scripts['/repair?task=ajax.mainJS']);
unset($doc->_script['text/javascript']);
//b0debug($doc->_scripts);
unset($doc->_styleSheets['/media/system/css/modal.css']);
unset($doc->_styleSheets['/components/com_cobalt/library/css/style.css']);
unset($doc->_styleSheets['/components/com_jce/editor/tiny_mce/plugins/columns/css/content.css']);
unset($doc->_styleSheets['/plugins/system/jce/css/content.css']);
//b0debug($doc->_styleSheets);

switch ($this->section->id) {
    case SparepartIds::ID_SECTION :
	    $product = new Product($this->item, new SparepartIds, $this->tmpl_params['record'], $this->appParams);
	    break;
    case AccessoryIds::ID_SECTION :
	    $product = new Product($this->item, new AccessoryIds, $this->tmpl_params['record'], $this->appParams);
	    break;
}
$this->document->setTitle($product->metaTitle);
$this->document->setMetaData('description', $product->metaDescription);
$this->document->setMetaData('keywords', $product->metaKeywords);
unset($this->item);
echo JLayoutHelper::render('b0.Product.openGraph', [
        'og' => $product->openGraph,
        'doc' => $this->document,
    ]);
echo JLayoutHelper::render('b0.Product.microdata', $product);
?>

<article class="uk-article">

<?= JLayoutHelper::render('b0.Product.controls-in-line', $product->controls) ?>

<h1 class="uk-text-center-small"><?= $product->title ?></h1>

<div class="uk-navbar">
    <div class="uk-navbar-nav">
	    <?= JLayoutHelper::render('b0.Product.subtitle', $product->subtitle) ?>
    </div>
    <div class="uk-navbar-flip">
	    <?= JLayoutHelper::render('b0.Product.productCode', $product->productCode) ?>
    </div>
</div>

<div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-2-5 uk-text-center">
        <ul id="tab-bottom-content" class="uk-switcher uk-margin">
            <li class="uk-active">
                <?= !$product->isHit ? $product->image['result'] : $product->hitImage['result']?>
            </li>
            <li>
	            <?= $product->video['result'] ?>
            </li>
        </ul>
        <ul class="uk-tab uk-tab-grid uk-tab-bottom" data-uk-tab="{connect:'#tab-bottom-content'}">
            <li class="uk-width-1-2 uk-active"><a href="#"><i class="uk-icon-camera uk-margin-right"></i>Фото</a></li>
            <li class="uk-width-1-2"><a href="#"><i class="uk-icon-video-camera uk-margin-right"></i>Видео</a></li>
        </ul>
    </div>
    <div class="uk-width-medium-3-5">
        <div class="uk-panel uk-panel-header uk-panel-box">
            <p class="uk-h3 uk-panel-title uk-text-center-small" style="color: #666666;"><i class="uk-icon-shopping-basket uk-margin-right"></i>Купить в магазине</p>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-2">
                    <?= JLayoutHelper::render('b0.Product.priceShop', $product); ?>
                    <a href="<?= $product->howToBuyUrl ?>" target="_blank" class="uk-button uk-button-success uk-width-1-1 uk-margin-top">
                        Как купить в магазине
                    </a>
                    <a href="<?= $product->howToInstallUrl ?>" target="_blank" class="uk-button uk-button-success uk-width-1-1 uk-margin-top">
                        Как установить в сервисе
                    </a>
                </div>
                <div class="uk-width-medium-1-2">
                    <?= JLayoutHelper::render('b0.Product.availability', [
                            'id' => $product->id,
                            'availability' => $product->availability,
                            'special' => $product->availabilitySpecial,
                        ]
                    )?>
            </div>
            </div>
        </div>

        <div class="uk-margin-top">
            <div class="uk-grid" data-uk-grid-margin>
                <!-- Блок корзины -->
                <div class="uk-width-medium-1-2" x-data="cartData()" x-init="cartMounted()">
                    <div class="uk-text-center-small">
                        <button x-show="cartQuantity===0" @click="addToCart()" type="button"
                                class="uk-button uk-button-danger uk-width-medium-1-1" <?= $product->isByOrder ? 'disabled' : ''?>>
                            <i class="uk-icon-cart-plus uk-margin-right"></i>В корзину
                        </button>
                        
                        <a x-show="cartQuantity>0" href="<?= JRoute::_('cart') ?>" rel="nofollow" target="_blank" class="uk-button uk-button-success uk-width-medium-1-1">
                            <i class="uk-icon-cart-plus uk-margin-right"></i>В корзинe
                        </a>
                    </div>
                </div>
                <!-- Блок заказа обратного звонка -->
                <div class="uk-width-medium-1-2">
                    <button class="uk-button uk-button-success uk-width-medium-1-1 contactus-<?= $product->moduleCallback ?>">
                        <i class="uk-icon-phone uk-margin-right"></i>Закажите обратный звонок
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="uk-grid uk-grid-match" data-uk-grid-match data-uk-grid-margin>
    <div class="uk-width-medium-2-5">
        <div class="uk-panel uk-panel-box uk-panel-box-secondary">
            <?php $product->renderField($product->manufacturer, 'p');?>
	        <?php $product->renderField($product->vendorCode, 'p');?>
	        <?php $product->renderField($product->originalCode, 'p');?>
	        <?php $product->renderField($product->model, 'p');?>
	        <?php $product->renderField($product->generation, 'p');?>
	        <?php $product->renderField($product->motor, 'p');?>
        </div>
    </div>
    <div class="uk-width-medium-3-5">
       <!-- Блок цен по дисконтным картам -->
        <?php if (!($product->isSpecial || $product->isOriginal)): ?>
            <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-3 uk-panel-teaser uk-text-center-small">
                        <img src="<?= $product->discountCardIcon ?>" alt="Дисконтная карта <?= $product->siteName ?>" />
                    </div>
                    <div class="uk-width-medium-2-3 uk-text-center-small">
                        <p class="uk-h4"><?php $product->renderField($product->priceSimple) ?></p>
                        <p class="uk-h4"><?php $product->renderField($product->priceSilver) ?></p>
                        <p class="uk-h4"><?php $product->renderField($product->priceGold) ?></p>
                        <p>
                            <a href="<?= $product->discountUrl ?>" target="_blank" title="Программа лояльности <?= $product->siteName ?>">
                                Программа лояльности <?= $product->siteName ?> <i class="uk-icon-external-link"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>

<hr class="uk-article-divider">
<!-- Описание -->
<?php if ($product->description['result']) {
    echo $product->description['result'];
} ?>
<!-- Характеристики -->
<?php if ($product->characteristics['result']) {
	echo '<p class="uk-h4">Характеристики</p>';
    echo $product->characteristics['result'];
} ?>
<!-- Закладки -->
<div class="uk-margin-large-top">
    <?= JLayoutHelper::render('b0.tabs', $product->tabs) ?>
</div>

<!-- Минибаннеры -->
<hr class="uk-article-divider">
<div>
    <?= JLayoutHelper::render('b0.module', [
        'title' => '',
        'id' => $product->moduleMinibanners,
    ]) ?>
</div>
<!-- Статистика -->
<div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-top">
    <div class="uk-flex uk-flex-middle uk-flex-space-around">
        <div class="uk-text-small">
            <?= JLayoutHelper::render('b0.hits', ['hits' => $product->hits]) ?>
        </div>
        <div>
            <?= JLayoutHelper::render('b0.discuss', ['href' => $product->vkUrl, 'src' => $product->vkIcon]) ?>
        </div>
    </div>
</div>
<div class="uk-margin-top">
    Вы можете купить <?= $product->title ?> в магазинах <?= $product->siteName ?> по доступной цене.
    <?= $product->title ?>: описание, фото, характеристики, аналоги и сопутствующие товары.
</div>
</article>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<!--<script src="https://unpkg.com/axios/dist/axios.min.js" defer></script>-->
<script src="/templates/b0/js/axios-1-5.min.js" defer></script>
<script>
    function cartData() {
        return {
            id: 0,
            cartQuantity: 0,
            addToCart(){
                axios.get('/index.php?option=com_lscart&task=cart.add&item_id='+this.id+'&item_quantity=1')
                    .then( (response)=>{
                        this.cartQuantity = 1;
                        let elemCartCount = document.getElementById('cart-count');
                        let elemCartCountSmall = document.getElementById('cart-count-small');
                        elemCartCount.textContent = String(Number(elemCartCount.textContent) + 1);
                        elemCartCountSmall.textContent = String(Number(elemCartCountSmall.textContent) + 1);
                    })
                    .catch( (error)=>{
                        console.log(error);
                        confirm(error);
                    });
            },
            cartMounted(){
                this.id = <?= $product->id ?>;
                this.cartQuantity = <?= $product->cart['quantity'] ?>;
            }
        }
    }
</script>
