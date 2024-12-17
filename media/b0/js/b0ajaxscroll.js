"use strict";
let ias = jQuery.ias({
    container:  "#articles",
    item:       ".uk-article",
    pagination: ".pagination",
    next:       ".pagination-next a"
});

ias.extension(new IASSpinnerExtension({
    html: '<div class="ias-spinner uk-text-center"><i class="uk-icon-refresh uk-icon-spin"></i></div>'
}));
ias.extension(new IASTriggerExtension({
    text: 'Показать еще 10',
    html: '<div class="ias-trigger ias-trigger-next uk-text-center"><a class="uk-button uk-button-primary">{text}</a></div>'
}));
ias.extension(new IASNoneLeftExtension({
    text: 'Конец списка'
}));