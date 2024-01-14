<?php
mgAddMeta('components/filter/applied/applied.css');

//исключаем бренды из фильтров
for ($i=0; $i < count($data); $i++) { 
    if($data[$i]['name'] === "Бренды[prop attr=плагин]"){
        unset($data[$i]);
    }
}

if (empty($data)) {
    $style = ' style="display:none"';
} else {
    $style = '';
} ?>

<div class="l-col min-0--12">
    <div class="c-apply apply-filter-line">
        <div class="c-apply__title apply-filter-title" <?php echo $style ?>>
            <?php echo lang('filterApplied'); ?>
        </div>
        <form action="?" class="c-apply__form apply-filter-form"
              data-print-res="<?php echo MG::getSetting('printFilterResult') ?>" <?php echo $style ?>>
            <ul class="c-apply__tags filter-tags">
                <?php foreach ($data as $property) {
                    $cellCount = 0;
                    ?>
                    <li class="c-apply__tags--item apply-filter-item">
                        <span class="c-apply__tags--name filter-property-name">
                            <?php echo $property['name'] . ": "; ?>
                        </span>

                        <?php if (in_array($property['values'][0], array('slider|easy', 'slider|hard', 'slider'))) {
                            ?>
                            <span class="c-apply__tags--value filter-price-range">
                                <?php echo lang('filterFrom') . "&nbsp;" . $property['values'][1] . "&nbsp;" . lang('filterTo') . "&nbsp;" . $property['values'][2]; ?>
                                <a role="button" href="javascript:void(0);" class="c-apply__tags--remove removeFilter">
                                    <svg class="icon icon--close"><use xlink:href="#icon--close"></use></svg>
                                </a>
                            </span>

                            <?php if ($property['code'] != "price_course"): ?>
                                <input name="<?php echo $property['code'] . "[" . $cellCount . "]" ?>"
                                       value="<?php echo $property['values'][0] ?>" type="hidden"/>
                                <?php $cellCount++; ?>
                            <?php endif; ?>

                            <input name="<?php echo $property['code'] . "[" . $cellCount . "]" ?>"
                                   value="<?php echo $property['values'][1] ?>" type="hidden"/>
                            <input name="<?php echo $property['code'] . "[" . ($cellCount + 1) . "]" ?>"
                                   value="<?php echo $property['values'][2] ?>" type="hidden"/>
                        <?php } else { ?>
                            <ul class="c-apply__tags--values filter-values">
                                <?php foreach ($property['values'] as $cell => $value) {
                                    ?>
                                    <li class="c-apply__tags--value apply-filter-item-value">
                                        <?php echo $value['name']; ?>
                                        <a role="button" href="javascript:void(0);"
                                           class="c-apply__tags--remove removeFilter">
                                            <svg class="icon icon--close">
                                                <use xlink:href="#icon--close"></use>
                                            </svg>
                                        </a>
                                        <input name="<?php echo $property['code'] . "[" . $cell . "]" ?>"
                                               value="<?php echo $property['values'][$cell]['val'] ?>" type="hidden"/>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>

                    </li>
                <?php } ?>
            </ul>
            <div class="c-apply__refresh">
                <a href="<?php echo SITE . URL::getClearUri() ?>"
                   class="c-button refreshFilter"><?php echo lang('filterReset'); ?></a>
            </div>
            <input type="hidden" name="applyFilter" value="1"/>
        </form>
    </div>
</div>
