<?php
defined('_JEXEC') or die('Restricted access');
JImport('b0.fixtures');

//$app = JFactory::getApplication();
//$doc = JFactory::getDocument();
/** @var JRegistry $paramsMarkup */
$paramsMarkup = $this->tmpl_params['markup'];
/** @var JRegistry $paramsList */
$paramsList = $this->tmpl_params['list'];

$Section = new stdClass();
$Section->sectionId = $this->section->id;   // string
//$Section->categoryId = $this->category->id;   // если есть категория - string, если нет - int(0)
$Section->siteName = JFactory::getApplication()->get('sitename');   // string

$Section->title = $this->section->title;   // string
$Section->description = $this->section->description;   // string
$Section->metaTitle = $this->section->params['more']->metakey;   // string
$Section->metaDescription = $this->section->params['more']->metadesc;   // string

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
    <div class="uk-width-medium-1-5">
        <div class="uk-panel uk-panel-box uk-text-center anchor">
            <a href="#our-partners">
                Наши партнеры
            </a>
        </div>
    </div>
    <div class="uk-width-medium-1-5">
        <div class="uk-panel uk-panel-box uk-text-center anchor">
            <a href="#partners-program">Партнерская программа</a>
        </div>
    </div>
</div>

<section id="our-partners">
    <div class="uk-panel uk-panel-box uk-margin-large-top">
        <h2 class="uk-text-center">
            Наши партнеры
            <a class="tm-totop-scroller" title="вернуться к оглавлению" href="#" data-uk-smooth-scroll=""></a>
        </h2>
    </div>

    <!-- Панель меню -->
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
						'typeName' => 'Партнера'
					]);
				endif; ?>
            </ul>
        </div>
    </div>

    <!-- Список статей -->
	<?php if($this->items):?>
		<?= $this->loadTemplate('list_'.$this->list_template) ?>
        <hr class="uk-article-divider">
	<?php endif;?>

    <form class="uk-form" method="post" action="<?= $this->action  ?>" name="adminForm"
          id="adminForm" enctype="multipart/form-data">
        <input type="hidden" name="section_id" value="<?= $this->state->get('records.section_id') ?>">
        <input type="hidden" name="cat_id" value="<?= JFactory::getApplication()->input->getInt('cat_id')  ?>">
        <input type="hidden" name="option" value="com_cobalt">
        <input type="hidden" name="task" value="">
        <input type="hidden" name="limitstart" value="0">
        <input type="hidden" name="filter_order" value="<?= $this->ordering ?>">
        <input type="hidden" name="filter_order_Dir" value="<?= $this->ordering_dir ?>">
		<?= JHtml::_('form.token') ?>
    </form>
</section>

<section id="partners-program">
    <div class="uk-panel uk-panel-box uk-margin-large-top">
        <h2 class="uk-text-center">
            Партнерская программа
            <a class="tm-totop-scroller" title="вернуться к оглавлению" href="#" data-uk-smooth-scroll=""></a>
        </h2>
    </div>
    <h3 class="uk-text-center uk-margin-large-top">Партнерская программа "Форумы, клубы, блоггеры"</h3>
    <div class="uk-margin-large-top">
        <p>Если вы являетесь администратором группы или сообщества, а также блога на драйве или канала на YouTube, мы готовы предложить для Вашей аудиториии дополнительные скидки и подарки, а также информационную поддержку.</p>
        <p>Мы открыты для любых предложений.</p>
        <p>Возможные формы сотрудничества лучше обсудить при личной встрече.</p>
        <p>Один из вариантов нашей партнерской программы:</p>
        <ul>
            <li>мы изготавливаем оговоренное количество дисконтных карт, дающих определенное преимущество для Вашей аудитории в нашем магазине и сервисе;</li>
            <li>с одной стороны карты размещается информация о Вашем ресурсе, с другой- о нашем;</li>
            <li>также мы готовы регулярно выделять аксссуары, запчасти или подарочные сертификаты для проведения розыгрышей на вашем ресурсе;</li>
            <li>обязательный обмен партнерскими ссылками для Вашего и нашего успешного развития.</li>
        </ul>
        <p>Если данная программы вам будет интересна, свяжитесь с нами по телефонам 8 (812) 928-32-17, 8 (800) 234-32-17 или напишите на <a href="mailto:partner@stovesta.ru">partner@stovesta.ru</a></p>
        <p>Контактное лицо по всем вопросам сотрудничества- Андрей Владимирович.</p>
    </div>
    <h3 class="uk-text-center uk-margin-large-top">Партнерская программа "Поставщики"</h3>
    <div class="uk-margin-large-top">
        <p>Если Вы являетесь Поставщиком продукции, интересной для нашей компании, мы готовы приобрести у вас партии производимых Вами товаров различного объема- от нескольких штук, до мелкого или среднего опта.</p>
        <p>Так же мы могли бы рассказать о Вас и показать примеры Вашей продукции многим потенциальным клиентам при помощи наших информационных ресурсов.</p>
        <p>Свяжитесь с нами по телефонам 8 (812) 928-32-17, 8 (800) 234-32-17 или напишите на <a href="mailto:partner@stovesta.ru">partner@stovesta.ru</a></p>
        <p>Контактное лицо по всем вопросам сотрудничества- Андрей Владимирович.</p>
    </div>
    <h3 class="uk-text-center uk-margin-large-top">Партнерская программа "Производители"</h3>
    <div class="uk-margin-large-top">
        <p>Если Вы являетесь Производителем продукции, интересной для нашей компании, свяжитесь с нами по телефонам 8 (812) 928-32-17, 8 (800) 234-32-17 или напишите на <a href="mailto:partner@stovesta.ru">partner@stovesta.ru</a></p>
        <p>Контактное лицо по всем вопросам сотрудничества- Андрей Владимирович.</p>
    </div>
</section>
