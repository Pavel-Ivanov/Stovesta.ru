<?php
defined('_JEXEC') or die();
JImport('b0.Company.CompanyConfig');
/**
 * @var array $displayData
 */
?>
<div itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="<?= $displayData->title?>" />
    <meta itemprop="description" content="<?= $displayData->metaDescription ?>" />
    <meta itemprop="sku" content="<?= $displayData->productCode ?>"/>
    <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
        <meta itemprop="url" content="<?= $displayData->image['url'] ?>" />
        <meta itemprop="image" content="<?= $displayData->image['url'] ?>" />
        <meta itemprop="height" content="<?= $displayData->image['height'] ?>" />
        <meta itemprop="width" content="<?= $displayData->image['width'] ?>" />
    </div>
    <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
        <meta itemprop="price" content="<?= ($displayData->isSpecial) ? $displayData->priceSpecial : $displayData->priceGeneral ?>"/>
        <meta itemprop="availability" content="InStock" />
        <meta itemprop="url" content="<?= JRoute::_($displayData->url, TRUE, 1)?>" />
        <meta itemprop="priceCurrency" content="RUB" />
        <meta itemprop="acceptedPaymentMethod" content="Наличные, Кредитная карта" />
        <meta itemprop="availableDeliveryMethod" content="Бесплатная доставка" />
        <meta itemprop="warranty" content="12 месяцев" />
        <meta itemprop="priceValidUntil" content="2020-12-31" />
        <div itemprop="seller" itemscope itemtype="http://schema.org/Organization">
            <meta itemprop="name" content="<?= $displayData->siteName ?>" />
            <meta itemprop="address" content="<?= CompanyConfig::COMPANY_ADDRESS ?>" />
            <meta itemprop="telephone" content="<?= CompanyConfig::COMPANY_TELEPHONE ?>" />
            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                <meta itemprop="url" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
                <meta itemprop="image" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
                <meta itemprop="height" content="<?= CompanyConfig::COMPANY_LOGO_HEIGHT ?>" />
                <meta itemprop="width" content="<?= CompanyConfig::COMPANY_LOGO_WIDTH ?>" />
            </div>
        </div>
    </div>
    <meta itemprop="manufacturer" content="<?= $displayData->manufacturer ?>"/>
    <meta itemprop="mpn" content="<?= $displayData->mpn ?>"/>
    <div itemprop="brand" itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="<?= $displayData->siteName ?>" />
        <meta itemprop="address" content="<?= CompanyConfig::COMPANY_ADDRESS ?>" />
        <meta itemprop="telephone" content="<?= CompanyConfig::COMPANY_TELEPHONE ?>" />
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <meta itemprop="url" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="image" content="<?= CompanyConfig::COMPANY_LOGO ?>" />
            <meta itemprop="height" content="<?= CompanyConfig::COMPANY_LOGO_HEIGHT ?>" />
            <meta itemprop="width" content="<?= CompanyConfig::COMPANY_LOGO_WIDTH ?>" />
        </div>
    </div>
</div>
