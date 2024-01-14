<?php /*
Template Name: Moguta
Author: Moguta
Version: 1.1.269
Edition: CLOUD
*/ ?>
<!DOCTYPE html>
<html <?php getHtmlAttributes() ?>>
<head>
    <?php
    // Выводим метатеги страницы, стили шаблона и плагинов, подключенные через mgAddMeta,
    // а также jquery из mg-core/scripts
    mgMeta("meta", "css", "jquery");
	?>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!--[if lte IE 9]>
    <link rel="stylesheet" type="text/css" href="<?php echo PATH_SITE_TEMPLATE ?>/css/reject/reject.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo PATH_SITE_TEMPLATE ?>/css/style-ie9.css"/>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <![endif]-->
    <?php
    // Добавляем предзагрузку файла стилей
    $mainStyleUrl = getMainStyleLink();
    if (!empty($mainStyleUrl) && $mainStyleUrl !== null) { ?><link rel="preload" href="<?php echo $mainStyleUrl ?>" as="style"><?php } ?>
    <?php mgAddMeta('css/common.css'); ?>
    <?php mgAddMeta('css/layout.css'); ?>
    <?php mgAddMeta('css/grid.css'); ?>
    <?php mgAddMeta('css/animate.css'); ?>
    <?php
    // Полифил для css-переменных
    mgAddMeta('lib/css-vars-ponyfill.js'); ?>
    

</head>

<body class="l-body <?php MG::addBodyClass('l-'); ?>" <?php backgroundSite(); ?>>

<?php

//phpinfo();
// Спрайт SVG иконок
// Необходим для корректного отображения большинства стандартных компонентов
component('svg');
?>

<?php
// Шапка сайта для ie9
layout('ie9');
?>

<?php
// Шапка сайта
// layout/layout_header.php
layout('header', $data);
?>

<?php
// Если это главная страница, выводим либо плагин «Слайдер акций», либо «Конструктор слайдов»
if (URL::isSection(null)) { ?>
    <?php if (class_exists('SliderAction')) { ?>
        [slider-action]
    <?php } elseif (class_exists('Slider')) {
        // Параметр id в шорткоде плагина может отличаться
        // Скопируйте шорткод из слайдера, который вы хотите вставить
        ?>
        [mgslider id="1"]
    <?php } ?>
<?php } ?>
<main class="l-main">
    <?php if(class_exists('TemporarySale')) { ?>
        <?php mgAddMeta('components/plugin-style/temporary-sale.css') ;?>
    <?php } ?>
    <div class="l-container">
        <div class="l-row">
            <?php
            if (MG::get('controller') == "controllers_catalog") {
                // Сайдбар
                // layout/layout_sidebar.php
                layout('sidebar');
            }
            ?>

            <?php
            // Главный контейнер с контентом страницы
            // layout/layout_page.php
            layout('page'); ?>
        </div>
    </div>
</main>
<?php
// Шапка сайта
// layout/layout_footer.php
layout('footer');
?>

<?php
// Плагин «Обратный звонок»
if (class_exists('BackRing')): ?>
    [back-ring]
<?php endif; ?>

<?php
// Всплывающее оповещение о добавлении товара в избранное
component('favorites/informer');
?>

<?php mgAddMeta('js/script.js'); ?>

<?php
// Подключение всех js-скриптов движка, плагинов, компонентов
// а также всех скриптов, подключенных через функции addScript и mgAddMeta
mgMeta('js'); ?>

</body>
</html>
