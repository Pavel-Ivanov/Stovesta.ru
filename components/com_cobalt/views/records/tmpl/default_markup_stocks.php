<?php
defined('_JEXEC') or die();

/** @var JRegistry $paramsMarkup */
$paramsMarkup = $this->tmpl_params['markup'];
/** @var JRegistry $paramsList */
$paramsList = $this->tmpl_params['list'];

$Section = new stdClass();
$Section->sectionId = $this->section->id;
$Section->siteName = JFactory::getApplication()->get('sitename');

$Section->title = $this->section->title;
$Section->description = $this->section->description;
$Section->metaTitle = $this->section->params['more']->metakey;
$Section->metaDescription = $this->section->params['more']->metadesc;

JFactory::getDocument()->setTitle($Section->metaTitle);
$this->document->setMetaData('description', $Section->metaDescription);
$this->document->setMetaData('keywords', '');
?>

<!-- Шапка раздела -->
<h1>
	<?= $Section->description ?>
</h1>

<hr class="uk-article-divider">
<div class="uk-grid uk-grid-match" data-uk-grid-match data-uk-grid-margin>
    <div class="uk-width-medium-1-2">
        <div class="uk-panel uk-panel-box uk-text-center anchor">
            <a href="#tekushchie-aktsii">Текущие акции</a>
        </div>
    </div>
    <div class="uk-width-medium-1-2">
        <div class="uk-panel uk-panel-box uk-text-center anchor">
            <a href="#discount-cards">Программа лояльности</a>
        </div>
    </div>
</div>
<!-- Текущие акции -->
<section id="tekushchie-aktsii">
    <div class="uk-panel uk-panel-box uk-margin-large-top">
        <h2 class="uk-text-center">
            Текущие акции
            <a class="tm-totop-scroller" title="вернуться к оглавлению" href="#" data-uk-smooth-scroll=""></a>
        </h2>
    </div>
    <!-- Панель меню -->
	<?php if($paramsMarkup->get('menu.menu')):?>
        <div class="uk-navbar uk-margin-top">
            <div class="uk-navbar-nav">
            </div>
            <div class="uk-navbar-flip">
                <ul class="uk-subnav uk-subnav-line">
                    <!-- Добавить запись -->
					<?php if(!empty($this->postbuttons)) :
						echo JLayoutHelper::render('b0.addItem', [
							'postButtons' => $this->postbuttons,
							'section' => $this->section,
							'category' => $this->category,
							'typeName' => 'Акцию'
						]);
					endif; ?>
                </ul>
            </div>
        </div>
	<?php endif; ?>
	
	<?php if($this->items):?>
        <!-- Индекс категории -->
        <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom">
            <ul class="uk-list">
				<?php foreach ($this->items as $item): ?>
                    <li><a href="#<?= $item->alias ?>"><?= $item->title ?></a></li>
				<?php endforeach;?>
            </ul>
        </div>
        <!-- Предупреждение -->
        <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-bottom">
            <p class="uk-h5 uk-text-warning">
                Внимание! Скидки по акциям не суммируются.
            </p>
        </div>
        <!-- Список статей -->
		<?= $this->loadTemplate('list_'.$this->list_template) ?>
	<?php endif;?>
</section>
<!-- Программа лояльности -->
<section id="discount-cards">
    <div class="uk-panel uk-panel-box uk-margin-large-top">
        <h2 class="uk-text-center">
            Программа лояльности
            <a class="tm-totop-scroller" title="вернуться к оглавлению" href="#" data-uk-smooth-scroll=""></a>
        </h2>
    </div>
    <h3 id="h1" class="uk-article-title uk-margin-top">Дисконтная карта</h3>
    <p><img src="/images/elements/discount-cards/discount-card-stovesta-new2024_ver2.png" alt="Дисконтная карта StoVesta" class="uk-float-right" /></p>
    <p>При первой покупке или обслуживании автомобиля выдается карта Стандартного уровня, по которой действует <span class="uk-text-danger">скидка 3%</span> на запчасти и аксессуары*, а также услуги сервиса.</p>
    <p>Карта является накопительной. Скидка по карте автоматически увеличивается в зависимости от общего объема покупок в магазине и сервисе.</p>
    <p>При достижении общей суммы покупок в магазине и сервисе на сумму 20000 рублей, карта достигает <span class="uk-text-muted">Серебряного уровня</span>, по которой действует <span class="uk-text-danger">скидка 5%</span> на запчасти и аксессуары, а также услуги сервиса.</p>
    <p>При достижении общей суммы покупок в магазине и сервисе на сумму 40000 рублей, карта достигает <span class="uk-text-warning">Золотого уровня</span>, по которой действует <span class="uk-text-danger">скидка 7%</span> на запчасти и аксессуары, а также услуги сервиса.</p>
    <p>Срок действия карты не ограничен.</p>
    <p>*скидка не распространяется на запчасти и аксессуары от оригинального производителя, а так же на товары с пометкой "Специальная цена".</p>
</section>
