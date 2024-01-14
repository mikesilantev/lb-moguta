<?php
/**
 *  Файл представления Favorites - выводит сгенерированную движком информацию на странице сайта с избранными товарами, который отметил пользователь.
 *  В этом  файле доступны следующие данные:
 *   <code>
 * 'items' => $items['catalogItems'],
 *    $data['items'] => Массив товаров,
 *    $data['titleCategory'] => Название открытой категории,
 *    $data['pager'] => html верстка  для навигации страниц,
 *    $data['meta_title'] => Значение meta тега для страницы,
 *    $data['meta_keywords'] => Значение meta_keywords тега для страницы,
 *    $data['meta_desc'] => Значение meta_desc тега для страницы,
 *    $data['currency'] => Текущая валюта магазина,
 *    $data['actionButton'] => тип кнопки в мини карточке товара
 *   </code>
 *
 *   Получить подробную информацию о каждом элементе массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <?php viewData($data['items']); ?>
 *   </code>
 *
 *   Вывести содержание элементов массива $data, можно вставив следующую строку кода в верстку файла.
 *   <code>
 *    <?php echo $data['items']; ?>
 *   </code>
 *
 *   <b>Внимание!</b> Файл предназначен только для форматированного вывода данных на страницу магазина. Категорически не рекомендуется выполнять в нем запросы к БД сайта или реализовывать сложную программную логику логику.
 * @author Гайдис Михаил
 * @package moguta.cms
 * @subpackage Views
 */
// Установка значений в метатеги title, keywords, description.
mgSEO($data);
?>
<style>
    .daily-wrapper,
    .l-main__left {
        display: none;
    }

    .l-main__right {
        flex: 0 0 100%;
        max-width: 100%;
    }
</style>

<!-- catalog - start -->
<div class="l-row">

    <!-- c-title - start -->
    <div class="l-col min-0--12">
        <h1 class="c-title"><?php echo $data['titleCategory'] ?></h1>
    </div>
    <!-- c-title - end -->

    <!-- c-switcher - start -->
  <?php component('catalog/switcher'); ?>
    <!-- c-switcher - end -->

    <!-- c-goods - start -->
    <div class="l-col min-0--12">
        <div class="c-goods products-wrapper js-products-container catalog">
            <div class="l-row">
              <?php foreach ($data['items'] as $item) { ?>
                  <div class="l-col min-0--6 min-768--4 min-990--3 min-1025--3 c-goods__trigger">
                    <?php
                    // Миникарточка товара
                    component(
                      'catalog/item',
                      ['item' => $item]
                    );
                    ?>
                  </div>
              <?php } ?>
            </div>
        </div>
    </div>
    <!-- c-goods - end -->


</div>
<!-- catalog - end -->