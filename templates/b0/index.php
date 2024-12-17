<!DOCTYPE HTML>
<html lang="ru-ru" dir="ltr">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <jdoc:include type="head" />
	<?php $this->setGenerator(null); ?>

    <link rel="stylesheet" href="/templates/b0/css/bootstrap.css">
    <link rel="stylesheet" href="/templates/b0/css/theme.min.css">
    <link rel="stylesheet" href="/templates/b0/css/custom.css">

    <script src="/templates/b0/js/uikit.js"></script>
    <script src="/templates/b0/js/theme.min.js"></script>
    <script src="/templates/b0/js/components/slideshow.js"></script>
    <script src="/templates/b0/js/components/lightbox.js"></script>
    <script src="/templates/b0/js/components/accordion.js"></script>
    <script src="/templates/b0/js/verticalDropdown.js"></script>
    
    <script src='https://salebot.pro/js/salebot.js'></script>
    <script>
        SaleBotPro.init({
            onlineChatId: '995'
        });
    </script>
</head>

<body  class="tm-isblog tm-sidebar-width-20">
    <div class="uk-grid tm-wrapper" data-uk-grid-match>
        <!-- Боковая панель -->
        <div class="uk-width-1-1 tm-sidebar-wrapper uk-hidden-medium uk-hidden-small">
            <div class="tm-sidebar-menu-container">
                <jdoc:include type="modules" name="sidebar-logo" />
                <jdoc:include type="modules" name="sidebar-menu" />
            </div>
        </div>
        <!-- Правая панель -->
        <div class="uk-width-1-1 tm-content-wrapper tm-footer-true">

            <div class="uk-panel uk-panel-box uk-hidden-small" style="border: none">
                <div class="uk-grid">
                    <div class="uk-width-9-10 uk-text-center">
                        <jdoc:include type="modules" name="relations" />
                    </div>
                    <div class="uk-width-1-10 uk-text-center">
                        <jdoc:include type="modules" name="social" />
                    </div>
                </div>
            </div>

            <div class="uk-panel uk-panel-box uk-margin-top-remove uk-hidden-small" style="border: none; padding-top: 0;">
                <div class="uk-grid">
                    <div class="uk-width-1-1 uk-text-center">
                        <jdoc:include type="modules" name="header" />
                    </div>
                </div>
            </div>

            <nav class="tm-navbar uk-navbar uk-visible-small">
                <div class="uk-grid">
                    <div class="uk-width-1-10">
                        <a href="#offcanvas" class="uk-navbar-toggle" data-uk-offcanvas></a>
                    </div>
                    <div class="uk-width-7-10 uk-text-center-small">
                        <a class="tm-logo-small" href="https://stovesta.ru">
                            <jdoc:include type="modules" name="logo-small" />
                        </a>
                    </div>
                    <div class="uk-width-2-10 uk-text-center-small">
                        <jdoc:include type="modules" name="cart-small" />
                    </div>
                </div>
            </nav>

            <div class="uk-margin-top uk-visible-small">
                <jdoc:include type="modules" name="header-small" />
            </div>

            <div class="tm-content-container">

                <section class="tm-top-a uk-grid uk-hidden-small" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <jdoc:include type="modules" name="top-a" />
                    </div>
                </section>

                <hr class="tm-grid-divider">
                <section class="tm-top-b uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <jdoc:include type="modules" name="top-b" />
                    </div>
                </section>

                <hr class="tm-grid-divider">
                <section class="tm-top-c uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <jdoc:include type="modules" name="top-c" />
                    </div>
                </section>

                <!-- Основной контент -->
                <hr class="tm-grid-divider">
                <div class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>
                    <div class="tm-main uk-width-medium-1-1">
                        <main class="tm-content">
                            <jdoc:include type="modules" name="breadcrumbs" />
                            <jdoc:include type="component" />
                        </main>
                    </div>
                </div>
            </div>

            <div class="tm-block-footer uk-text-center uk-text-muted">
                <jdoc:include type="modules" name="footer" />
                <jdoc:include type="modules" name="debug" />
            </div>

            <footer class="tm-footer uk-flex uk-flex-middle uk-flex-center">
                <a class="tm-totop-scroller" data-uk-smooth-scroll href="#"></a>
            </footer>
        </div>
    </div>

    <div id="offcanvas" class="uk-offcanvas">
        <div class="uk-offcanvas-bar">
            <ul class="uk-nav uk-nav-offcanvas">
                <li><a href="/feedback">Клиентский сервис</a></li>
                <li><a href="/spareparts">Запчасти</a></li>
                <li><a href="/accessories">Аксессуары</a></li>
                <li><a href="/discounts">Скидки и акции</a></li>
                <li><a href="/maintenance">Техобслуживание</a></li>
                <li><a href="/repair">Ремонт</a></li>
                <li><a href="/news">Новости</a></li>
                <li><a href="/helpful">Полезное</a></li>
                <li><a href="/about-us">О нас</a></li>
                <li><a href="/contacts">Контакты</a></li>
            </ul>
        </div>
    </div>
    <!-- Top.Mail.Ru counter -->
    <script>
        var _tmr = window._tmr || (window._tmr = []);
        _tmr.push({id: "3282284", type: "pageView", start: (new Date()).getTime()});
        (function (d, w, id) {
            if (d.getElementById(id)) return;
            var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
            ts.src = "https://top-fwz1.mail.ru/js/code.js";
            var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
            if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
        })(document, window, "tmr-code");
    </script>
    <noscript><div><img src="https://top-fwz1.mail.ru/counter?id=3282284;js=na" style="position:absolute;left:-9999px;" alt="Top.Mail.Ru" /></div></noscript>
    <!-- /Top.Mail.Ru counter -->
</body>
</html>
