<?php mgAddMeta('components/favorites/btns/btns.css') ?>
<?php mgAddMeta('components/favorites/btns/btns.js') ?>

<?php
if (in_array(EDITION, array('market', 'gipermarket', 'saas')) && MG::getSetting('useFavorites') == 'true') {

  $favorites = explode(',', $_COOKIE['favorites']);
  if (in_array($data['id'], $favorites)) {
    $_fav_style_add = 'display:none;';
    $_fav_style_remove = '';
  } else {
    $_fav_style_add = '';
    $_fav_style_remove = 'display:none;';
  }
  ?>

    <a role="button"
       href="javascript:void(0);"
       data-item-id="<?php echo $data['id']; ?>"
       class="mg-remove-to-favorites js-remove-to-favorites <?php if (MG::get('controller') == "controllers_product"): ?>mg-remove-to-favorites--product<?php endif; ?>"
       style="<?php echo $_fav_style_remove ?>">
        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 47.94 47.94">
            <path d="M26.285 2.486l5.407 10.956c.376.762 1.103 1.29 1.944 1.412l12.091 1.757c2.118.308 2.963 2.91 1.431 4.403l-8.749 8.528c-.608.593-.886 1.448-.742 2.285l2.065 12.042c.362 2.109-1.852 3.717-3.746 2.722l-10.814-5.685c-.752-.395-1.651-.395-2.403 0l-10.814 5.685c-1.894.996-4.108-.613-3.746-2.722l2.065-12.042c.144-.837-.134-1.692-.742-2.285L.783 21.014c-1.532-1.494-.687-4.096 1.431-4.403l12.091-1.757c.841-.122 1.568-.65 1.944-1.412l5.407-10.956c.946-1.919 3.682-1.919 4.629 0z"/>
        </svg>
        <span class="remove__text"><?php echo lang('inFav') ?></span>
        <span class="remove__hover"><?php echo lang('delFav') ?></span>
    </a>

    <a role="button"
       href="javascript:void(0);"
       data-item-id="<?php echo $data['id']; ?>"
       class="mg-add-to-favorites js-add-to-favorites <?php if (MG::get('controller') == "controllers_product"): ?>mg-add-to-favorites--product<?php endif; ?>"
       style="<?php echo $_fav_style_add ?>">
        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 47.94 47.94">
            <path d="M26.285 2.486l5.407 10.956c.376.762 1.103 1.29 1.944 1.412l12.091 1.757c2.118.308 2.963 2.91 1.431 4.403l-8.749 8.528c-.608.593-.886 1.448-.742 2.285l2.065 12.042c.362 2.109-1.852 3.717-3.746 2.722l-10.814-5.685c-.752-.395-1.651-.395-2.403 0l-10.814 5.685c-1.894.996-4.108-.613-3.746-2.722l2.065-12.042c.144-.837-.134-1.692-.742-2.285L.783 21.014c-1.532-1.494-.687-4.096 1.431-4.403l12.091-1.757c.841-.122 1.568-.65 1.944-1.412l5.407-10.956c.946-1.919 3.682-1.919 4.629 0z"/>
        </svg>
      <?php echo lang('toFav') ?>
    </a>

<?php } ?>