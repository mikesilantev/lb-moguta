<!-- compare - start -->
<?php
mgAddMeta('components/compare/compare.js');
mgAddMeta('components/compare/compare.css');
mgAddMeta('components/catalog/item/item.css');
//mg::loger($data);
?>

<?php
if (!empty($data['catalogItems'])) { ?>
    <div class="c-compare mg-compare-products js-compare-page">
        <!-- top - start -->
        <div class="c-compare__top   mg-compare-left-side">
            <?php if (!empty($_SESSION['compareList'])) { ?>

                <div class="c-compare__top__select   mg-category-list-compare">
                    <?php if (MG::getSetting('compareCategory') != 'true') { ?>
                        <form class="c-form c-form--width">
                            <select name="viewCategory" onChange="this.form.submit()">
                                <?php foreach ($data['arrCategoryTitle'] as $id => $value): ?>
                                    <option value='<?php echo $id ?>' <?php
                                    if ($_GET['viewCategory'] == $id) {
                                        echo "selected=selected";
                                    }
                                    ?> ><?php echo $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    <?php } ?>
                </div>

                <div class="c-compare__top__buttons">
                    <a class="c-button c-compare__clear   mg-clear-compared-products"
                       href="<?php echo SITE ?>/compare?delCompare=1">
                        <svg class="icon icon--remove">
                            <use xlink:href="#icon--remove"></use>
                        </svg>
                        <?php echo lang('compareClean'); ?>
                    </a>
                    <a class="c-button" href="<?php echo SITE ?>">
                        <svg class="icon icon--arrow-left">
                            <use xlink:href="#icon--arrow-left"></use>
                        </svg>
                        <?php echo lang('compareBack'); ?>
                    </a>
                </div>

            <?php } ?>
        </div>
        <!-- top - end -->


        <!-- right block - start -->
        <div class="c-compare__right js-scroll-container">

            <!-- items - start -->
            <div class="c-compare__wrapper mg-compare-product-wrapper">

                <?php if (!empty($data['catalogItems'])) {
                    $dataProperty = [];
                    foreach ($data['catalogItems'] as $item) { ?>
                        <div class="c-goods__item c-compare__item mg-compare-product js-compare-item">
                            <div class="c-goods__left">
                                <a class="c-compare__remove mp-remove-compared-product"
                                   href="<?php echo SITE ?>/compare?delCompareProductId=<?php echo $item['id'] ?>">
                                    <svg class="icon icon--close">
                                        <use xlink:href="#icon--close"></use>
                                    </svg>
                                </a>
                                <a class="c-goods__img c-compare__img" href="<?php echo $item['link'] ?>">
                                    <?php echo mgImageProduct($item); ?>
                                </a>
                            </div>
                            <div class="c-goods__right">
                                <div class="c-goods__price mg-compare-product-list product-status-list">
                                    <?php if ($item["old_price"] != ""): ?>
                                        <s class="c-goods__price--old old-price" <?php echo (!$item['old_price']) ? 'style="display:none"' : '' ?>>
                                            <?php echo MG::numberFormat($item['old_price']). " " . $item['currency']; ?>
                                        </s>
                                    <?php endif; ?>
                                    <div class="c-goods__price--current price">
                                        <?php echo $item['price'] ?> <?php echo $item['currency']; ?>
                                    </div>
                                </div>
                                <a class="c-goods__title" href="<?php echo $item['link'] ?>">
                                    <?php echo $item['title'] ?>
                                </a>
                                <div class="c-compare__property">
                                    <?php echo $item['propertyForm'] ?>
                                </div>
                                <?php 
                                foreach ($item['propertyForm']['stringsProperties']['unGroupProperty'] as $v) {
                                    $dataProperty['unGroupProperty'][$v['name_prop']][$item['id']] = $v['name'];
                                }
                                foreach ($item['propertyForm']['stringsProperties']['groupProperty'] as $key => $val) {
                                    foreach($val['property'] as $k => $v){
                                        $dataProperty[$val['name_group']][$v['key_prop']][$item['id']] = $v['name_prop'];
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <?php $prodIds[] = $item['id'];
                    }
                } ?>
            </div>
            <!-- items - end -->
            <?php
                foreach ($dataProperty as $group => $propName) {
                    foreach ($propName as $name => $prop) {
                        foreach ($prodIds as $id) {
                            if (empty($prop[$id])) {
                                $dataProperty[$group][$name][$id] = '-';
                                ksort($dataProperty[$group][$name]);
                            }
                        }
                    }
                }
            ?>
            <!-- right table - start -->
            <div class="c-compare__table c-compare__table__right  mg-compare-fake-table-right ">
                <?php foreach ($dataProperty as $group => $propName) { 
                    $nullCell = 0; // для пустых ячеек
                    ?>
                    <?php foreach ($propName as $name => $prop) { ?>
                        <?php if($nullCell == 0 && $group != 'unGroupProperty'):?>
                            <div class="c-compare__row   mg-compare-fake-table-row">
                                <?php 
                                    while($nullCell < count($prop)){
                                        echo '<div class="c-compare__column   mg-compare-fake-table-cell"></div>';
                                        $nullCell++;
                                    }
                                ?>
                            </div> 
                        <?php endif; ?>
                        <div class="c-compare__row   mg-compare-fake-table-row">
                            <?php foreach ($prop as $id => $value) { ?>
                                <div class="c-compare__column   mg-compare-fake-table-cell">
                                    <?php echo $value;?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- right table - start -->
        </div>
        <!-- right block - end -->


        <!-- left block - start -->
        <div class="c-compare__left">
            <!-- left table - start -->
            <div class="c-compare__table c-compare__table__left  mg-compare-fake-table">
                <div class="c-compare__row   mg-compare-fake-table-left <?php echo $data['moreThanThree'] ?>">
                    <?php
                     foreach ($dataProperty as $group => $propName) { ?>
                          <?php if($group != 'unGroupProperty'): ?>
                            <div class="c-compare__column   mg-compare-fake-table-cell">
                                <div style="font-size: 14px; text-decoration: underline;"  title="<?php echo $group ?>">
                                    <?php echo $group ?>
                                </div>
                            </div>
                          <?php endif; ?>
                        <?php foreach ($propName as $name => $prop) { ?>
                            <div class="c-compare__column   mg-compare-fake-table-cell <?php if (trim($data['property'][$name]) !== '') : ?>with-tooltip<?php endif; ?>">

                                <div class="compare-text" title="<?php echo $name ?>">
                                    <?php echo $name ?>
                                </div>
                                <?php if (trim($data['property'][$name]) !== '') : ?>
                                    <div class="mg-tooltip">?
                                        <div class="mg-tooltip-content"
                                            style="display:none;"><?php echo $data['property'][$name] ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <!-- left table - end -->
        </div>
        <!-- left block - end -->
    </div>
<?php } ?>
<!-- compare - end -->
