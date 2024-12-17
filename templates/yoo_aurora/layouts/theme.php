<?php
// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html lang="ru-ru" dir="ltr"  data-config='{"twitter":0,"plusone":0,"facebook":0,"style":"logan-shop"}'>

<head>
    <?= $this['template']->render('head'); ?>
    <link rel="manifest" href="manifest.json">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/b0sw.js')
                .then(function(registration) {
                    console.log('Registration successful, scope is:', registration.scope);
                })
                .catch(function(error) {
                    console.log('Service worker registration failed, error:', error);
                });
        }
    </script>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name" : "StoVesta- автозапчасти, ремонт и техобслуживание Lada Vesta, Lada XRay, Lada Granta FL и Lada Largus в СПб",
            "url": "https://stovesta.ru"
        }
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){
            w[l]=w[l]||[];
            w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
            var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!=='dataLayer'?'&l='+l:'';
            j.async=true;
            j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WF9ZLD2');
    </script>
    <!-- End Google Tag Manager -->
    <script>
        !function(){var t=document.createElement("script");t.async=!0,t.src='https://vk.com/js/api/openapi.js?169',t.onload=function(){VK.Retargeting.Init("VK-RTRG-1455432-7FfTe"),VK.Retargeting.Hit()},document.head.appendChild(t)}();
    </script>
</head>

<body class="<?= $this['config']->get('body_classes') ?>">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WF9ZLD2"
        height="0" width="0" style="display:none;visibility:hidden">
    </iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<noscript>
    <img src="https://vk.com/rtrg?p=VK-RTRG-1455432-7FfTe" style="position:fixed; left:-999px;" alt=""/>
</noscript>

<div class="uk-grid tm-wrapper" data-uk-grid-match>

    <?php if ($this['widgets']->count('sidebar-main + sidebar-menu + sidebar-logo')) : ?>
    <div class="uk-width-1-1 tm-sidebar-wrapper uk-hidden-medium uk-hidden-small">

        <?php if ($this['widgets']->count('sidebar-menu + sidebar-logo')) : ?>
        <div class="tm-sidebar-menu-container" <?= $this['config']->get('sticky_navbar') ? 'data-uk-sticky' : '' ?>>
            <?php if ($this['widgets']->count('sidebar-logo')) : ?>
                <?= $this['widgets']->render('sidebar-logo') ?>
            <?php endif; ?>

            <?php if ($this['widgets']->count('sidebar-menu')) : ?>
            <nav class="tm-sidebar-nav">
                <?= $this['widgets']->render('sidebar-menu') ?>
            </nav>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('sidebar-main + sidebar-social')) : ?>
        <div class="tm-sidebar-widget-container">
            <?php if ($this['widgets']->count('sidebar-main')) : ?>
            <div class="tm-sidebar-main">
                <?= $this['widgets']->render('sidebar-main') ?>
            </div>
            <?php endif; ?>

            <?php if ($this['widgets']->count('sidebar-social')) : ?>
            <div class="tm-sidebar-social uk-flex uk-flex-middle uk-flex-center">
                <?= $this['widgets']->render('sidebar-social') ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="uk-width-1-1 tm-content-wrapper<?= (($this['widgets']->count('footer-menu')) || $this['config']->get('totop_scroller', true)) ? ' tm-footer-true' : '' ?>">

    <?php if ($this['widgets']->count('toolbar + search')) : ?>
        <div class="tm-toolbar uk-flex uk-flex-middle uk-flex-space-between uk-hidden-small uk-hidden-medium">

            <?php if ($this['widgets']->count('toolbar')) : ?>
                <div><?= $this['widgets']->render('toolbar') ?></div>
            <?php endif; ?>

            <?php if ($this['widgets']->count('search')) : ?>
                <div class="tm-search uk-text-right">
                    <?= $this['widgets']->render('search') ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('relations + social')) : ?>
        <div class="uk-panel uk-panel-box uk-hidden-small" style="background: #fafafa;">
            <div class="uk-grid">
                <div class="uk-width-9-10 uk-text-center">
                    <?= $this['widgets']->render('relations') ?>
                </div>
                <div class="uk-width-1-10 uk-text-center">
                    <?= $this['widgets']->render('social') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this['widgets']->count('header + cart')) : ?>
        <div class="uk-panel uk-panel-box uk-margin-top-remove uk-hidden-small" style="background: #fafafa;">
            <div class="uk-grid">
                <div class="uk-width-1-1 uk-text-center">
                    <?= $this['widgets']->render('header') ?>
                </div>
<!--                <div class="uk-width-1-10 uk-text-center">
                    <?/*= $this['widgets']->render('cart') */?>
                </div>
-->            </div>
        </div>
    <?php endif; ?>
    <?php if ($this['widgets']->count('offcanvas + logo-small')) : ?>
        <nav class="tm-navbar uk-navbar uk-visible-small">
            <div class="uk-grid">
                <div class="uk-width-1-10">
                    <?php if ($this['widgets']->count('offcanvas')) : ?>
                        <a href="#offcanvas" class="uk-navbar-toggle" data-uk-offcanvas></a>
                    <?php endif; ?>
                </div>
                <div class="uk-width-7-10 uk-text-center-small">
                    <?php if ($this['widgets']->count('logo-small')) : ?>
                        <a class="tm-logo-small" href="<?php echo $this['config']->get('site_url'); ?>">
                            <?= $this['widgets']->render('logo-small') ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="uk-width-2-10 uk-text-center-small">
	                <?php if ($this['widgets']->count('cart-small')) :
                        echo $this['widgets']->render('cart-small');
                    endif; ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <?php if ($this['widgets']->count('header-small')) : ?>
        <div class="uk-visible-small uk-margin-top">
            <?= $this['widgets']->render('header-small') ?>
        </div>
    <?php endif; ?>

        <?php if ($this['widgets']->count('content-top')) : ?>
        <div class="tm-block-content-top">
            <section class="<?= $grid_classes['content-top']; echo $display_classes['content-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('content-top', array('layout'=>$this['config']->get('grid.content-top.layout'))); ?></section>
        </div>
        <?php endif; ?>

        <div class="tm-content-container">

            <?php if ($this['widgets']->count('top-a')) : ?>
            <section class="<?= $grid_classes['top-a']; echo $display_classes['top-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?></section>
            <?php endif; ?>

            <?php if ($this['widgets']->count('top-b')) : ?>
            <hr class="tm-grid-divider">
            <section class="<?= $grid_classes['top-b']; echo $display_classes['top-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?></section>
            <?php endif; ?>

            <?php if ($this['widgets']->count('top-c')) : ?>
                <hr class="tm-grid-divider">
            <section class="<?php echo $grid_classes['top-c']; echo $display_classes['top-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-c', array('layout'=>$this['config']->get('grid.top-c.layout'))); ?></section>
            <?php endif; ?>

            <?php if ($this['widgets']->count('top-d')) : ?>
                <hr class="tm-grid-divider">
            <section class="<?= $grid_classes['top-d']; echo $display_classes['top-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-d', array('layout'=>$this['config']->get('grid.top-d.layout'))); ?></section>
            <?php endif; ?>

            <?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
            <hr class="tm-grid-divider">
            <div class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>

                <?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
                <div class="<?= $columns['main']['class'] ?>">

                    <?php if ($this['widgets']->count('main-top')) : ?>
                    <section class="<?= $grid_classes['main-top']; echo $display_classes['main-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?></section>
                    <?php endif; ?>

                    <?php if ($this['config']->get('system_output', true)) : ?>
                    <main class="tm-content">

                        <?php if ($this['widgets']->count('breadcrumbs')) : ?>
                            <?= $this['widgets']->render('breadcrumbs') ?>
                        <?php endif; ?>

                        <?= $this['template']->render('content') ?>

                    </main>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('main-bottom')) : ?>
                    <section class="<?= $grid_classes['main-bottom']; echo $display_classes['main-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?></section>
                    <?php endif; ?>

                </div>
                <?php endif; ?>

                <?php foreach($columns as $name => &$column) : ?>
                <?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
                <aside class="<?= $column['class'] ?>"><?php echo $this['widgets']->render($name) ?></aside>
                <?php endif ?>
                <?php endforeach ?>

            </div>
            <?php endif; ?>

            <?php if ($this['widgets']->count('bottom-a')) : ?>
            <hr class="tm-grid-divider">
            <section class="<?= $grid_classes['bottom-a']; echo $display_classes['bottom-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?></section>
            <?php endif; ?>

            <?php if ($this['widgets']->count('bottom-b')) : ?>
            <hr class="tm-grid-divider">
            <section class="<?= $grid_classes['bottom-b']; echo $display_classes['bottom-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
            <?php endif; ?>

            <?php if ($this['widgets']->count('bottom-c')) : ?>
            <hr class="tm-grid-divider">
            <section class="<?= $grid_classes['bottom-c']; echo $display_classes['bottom-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('bottom-c', array('layout'=>$this['config']->get('grid.bottom-c.layout'))); ?></section>
            <?php endif; ?>

            <?php if ($this['widgets']->count('bottom-d')) : ?>
            <hr class="tm-grid-divider">
            <section class="<?= $grid_classes['bottom-d']; echo $display_classes['bottom-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?= $this['widgets']->render('bottom-d', array('layout'=>$this['config']->get('grid.bottom-d.layout'))); ?></section>
            <?php endif; ?>
        </div>

        <?php if ($this['widgets']->count('content-bottom')) : ?>
        <div class="tm-block-content-bottom">
            <section class="<?= $grid_classes['content-bottom']; echo $display_classes['content-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('content-bottom', array('layout'=>$this['config']->get('grid.content-bottom.layout'))); ?></section>
        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('footer + debug') || $this['config']->get('warp_branding', true)) : ?>
        <div class="tm-block-footer uk-text-center uk-text-muted">

            <?php
            echo $this['widgets']->render('footer');
            $this->output('warp_branding');
            echo $this['widgets']->render('debug');
            ?>

        </div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('footer-menu') || $this['config']->get('totop_scroller', true)) : ?>
        <footer class="tm-footer uk-flex uk-flex-middle uk-flex-center">

            <?php if ($this['config']->get('totop_scroller', true)) : ?>
            <a class="tm-totop-scroller" data-uk-smooth-scroll href="#"></a>
            <?php endif; ?>

            <?= $this['widgets']->render('footer-menu') ?>

        </footer>
        <?php endif; ?>
    </div>
</div>

<?= $this->render('footer'); ?>

<?php if ($this['widgets']->count('offcanvas')) : ?>
<div id="offcanvas" class="uk-offcanvas">
    <div class="uk-offcanvas-bar"><?= $this['widgets']->render('offcanvas'); ?></div>
</div>
<?php endif; ?>

</body>
</html>
