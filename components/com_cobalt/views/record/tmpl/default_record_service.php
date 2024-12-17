<?php
defined('_JEXEC') or die();

JImport('b0.Service.Service');
JImport('b0.Work.WorkIds');

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
unset($doc->_script['text/javascript']);
//b0debug($doc->_scripts);
unset($doc->_styleSheets['/media/system/css/modal.css']);
unset($doc->_styleSheets['/components/com_cobalt/library/css/style.css']);
unset($doc->_styleSheets['/components/com_jce/editor/tiny_mce/plugins/columns/css/content.css']);
unset($doc->_styleSheets['/plugins/system/jce/css/content.css']);
//b0debug($doc->_styleSheets);

/** @var JRegistry $paramsApp */
$paramsApp = $this->appParams;
/** @var JRegistry $paramsRecord */
$paramsRecord = $this->tmpl_params['record'];

$service = new Service($this->item, new WorkIds, $this->tmpl_params['record'], $this->appParams);
unset($this->item);

$this->document->setTitle($service->metaTitle);
$this->document->setMetaData('description', $service->metaDescription);
echo JLayoutHelper::render('b0.Service.openGraph', [
	'og' => $service->openGraph,
	'doc' => $this->document,
]);
echo JLayoutHelper::render('b0.Service.microdata', $service);
?>

<section class="uk-article">
    <?php //TODO сделать проверку на наличие ?>
	<?= JLayoutHelper::render('b0.Service.controls-in-line', $service->controls) ?>

    <h1 class="uk-text-center-small"><?= $service->title ?></h1>

    <nav class="uk-navbar">
        <div class="uk-navbar-nav">
			<?= JLayoutHelper::render('b0.Service.subtitle', $service->subtitle) ?>
        </div>
        <div class="uk-navbar-flip">
			<?= JLayoutHelper::render('b0.Service.serviceCode', $service->serviceCode) ?>
        </div>
    </nav>

    <div class="uk-grid uk-grid-match" data-uk-grid-match data-uk-grid-margin>
        <div class="uk-width-medium-2-5 uk-text-center">
            <ul id="tab-bottom-content" class="uk-switcher uk-margin">
                <li class="uk-active">
					<?= $service->image['result'] ?>
                </li>
                <li>
					<?= $service->video['result'] ?>
                </li>
            </ul>
            <ul class="uk-tab uk-tab-grid uk-tab-bottom" data-uk-tab="{connect:'#tab-bottom-content'}">
                <li class="uk-width-1-2 uk-active"><a href="#">Фото</a></li>
                <li class="uk-width-1-2"><a href="#">Видео</a></li>
            </ul>
        </div>

        <div class="uk-width-medium-3-5">
            <!-- Блок цен -->
	        <?php if ($service->isSpecial) :?>
                <p class="b0-price b0-price-first uk-text-danger uk-margin-top">
			        <?= $service->priceSpecial['label'] . ' : ' . $service->priceSpecial['result'] ?>
                </p>
                <p class="b0-price b0-price-second uk-margin-top">
			        <del><?= $service->priceGeneral['label'] . ' : ' . $service->priceGeneral['result'] ?></del>
                </p>
                <p class="uk-margin-top">
                    Вы экономите <?= $service->renderEconomy($service->priceGeneral['value'], $service->priceSpecial['value'])?>
                </p>
	        <?php else:?>
                <p class="b0-price b0-price-first uk-text-center-small uk-margin-top">
			        <?= $service->priceGeneral['label'] . ' : ' . $service->priceGeneral['result'] ?>
                </p>
	        <?php endif ?>
            <div class="uk-panel uk-panel-box uk-margin-top">
                <!-- Блок записи на сервис -->
		        <?php if (!$service->isSpecial) :?>
                    <p class="b0-price b0-price-first uk-text-center-small uk-margin-top">
				        <?= $service->priceFirstVisit['label'] ?> : <?= $service->priceFirstVisit['result'] ?>
                    </p>
                    <p class="uk-text-danger">
                        <?= 'Вы экономите ' . $service->renderEconomy($service->priceGeneral['value'], $service->priceFirstVisit['value']) ?>
                        <span class="uk-text-small uk-margin-left">
                            (В рамках <a href="<?= $service->firstVisitUrl ?>" target="_blank" title="Условия акции Приятное знакомство">
                                акции "Приятное знакомство" <i class="uk-icon-external-link"></i></a>)
                        </span>
                    </p>
		        <?php endif ?>
                <button type="button" class="uk-width-1-1 uk-button uk-button-success uk-button-large contactus-<?= $service->moduleOrder ?>">
                    Записаться на сервис
                </button>
                <div class="uk-margin-top">
                    <p>
                        <strong>Свободное время:</strong> сегодня
                    </p>
                    <p>
                        <strong>Оплата:</strong> наличные, кредитная карта
                    </p>
                    <p>
                        <strong>Запишитесь по телефонам: </strong>
                        <a class="uk-h5" href="<?= $service->phoneService1['url'] ?>">
					        <?= $service->phoneService1['phone'] ?>
                        </a>
                        или
                        <a class="uk-h5" href="<?= $service->phoneService2['url'] ?>">
					        <?= $service->phoneService2['phone'] ?>
                        </a>
                    </p>
                </div>
                <!-- Блок заказа звонка -->
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-2">
                        <i class="uk-icon-question-circle uk-margin-right"></i>
                        <a href="<?= $service->guaranteeUrl ?>" target="_blank" title="Посмотреть условия гарантии">
                            Условия гарантии <i class="uk-icon-external-link"></i>
                        </a>
                    </div>
                    <div class="uk-width-medium-1-2">
                        <i class="uk-icon-phone uk-margin-right"></i>
                        <a class="contactus-<?= $service->moduleCallback ?>" href="#" title="Мы перезвоним Вам в течение часа в рабочее время">
                            Заказать обратный звонок
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-grid uk-grid-match" data-uk-grid-match data-uk-grid-margin>
        <div class="uk-width-medium-2-5">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary">
				<?php $service->renderField($service->model, 'p');?>
				<?php $service->renderField($service->motor, 'p');?>
            </div>
        </div>
        <div class="uk-width-medium-3-5">
            <!-- Блок цен по дисконтным картам -->
			<?php if (!$service->isSpecial): ?>
                <div class="uk-panel uk-panel-box uk-panel-box-secondary">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3 uk-panel-teaser uk-text-center-small">
                            <img src="<?= $service->discountCardIcon ?>" alt="Дисконтная карта <?= $service->siteName ?>" />
                        </div>
                        <div class="uk-width-medium-2-3 uk-text-center-small">
                            <p class="uk-h4"><?php $service->renderField($service->priceSimple) ?></p>
                            <p class="uk-h4"><?php $service->renderField($service->priceSilver) ?></p>
                            <p class="uk-h4"><?php $service->renderField($service->priceGold) ?></p>
                            <p>
                                <a href="<?= $service->discountsUrl ?>" target="_blank" title="Программа лояльности <?= $service->siteName ?>">
                                    Программа лояльности <?= $service->siteName ?> <i class="uk-icon-external-link"></i>
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
	<?php if ($service->description['result']) {
		echo $service->description['result'];
	} ?>
    <!-- Закладки -->
    <div class="uk-margin-large-top">
		<?= JLayoutHelper::render('b0.tabs', $service->tabs) ?>
    </div>
    <!-- Минибаннеры -->
    <hr class="uk-article-divider">
    <div>
        <?= JLayoutHelper::render('b0.module', [
                'title' => '',
                'id' => $service->moduleMinibanners,
            ]) ?>
    </div>
    <!-- Статистика -->
    <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin-top">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="uk-text-small">
				<?= JLayoutHelper::render('b0.hits', ['hits' => $service->hits]) ?>
            </div>
            <div>
                <?= JLayoutHelper::render('b0.discuss', ['href' => $service->vkUrl, 'src' => $service->vkIcon]) ?>
            </div>
        </div>
    </div>
</section>
