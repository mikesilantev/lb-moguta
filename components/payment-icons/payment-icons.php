<?php mgAddMeta('components/payment-icons/payment-icons.css'); ?>

<?php
$paymentIdToIconName = [
  '1' => 'webmoney.png',
  '2' => 'ya.png',
  '12' => 'ya.png',
  '5' => 'robo.png',
  '6' => 'qiwi.png',
  '8' => 'sci.png',
  '9' => 'payanyway.png',
  '10' => 'paymaster.png',
  '11' => 'alfabank.png',
  '14' => 'yandexkassa.png',
  '15' => 'privat24.png',
  '16' => 'liqpay.png',
  '17' => 'sber.png',
  '18' => 'tinkoff.png',
  '19' => 'paypal.png',
  '21' => 'paykeeper.png',
  '20' => 'comepay.svg',
  '22' => 'cloudpayments.png',
  '23' => 'ya-pay-parts.svg',
  '24' => 'yandexkassa.png',
  '25' => 'apple.png',
  '26' => 'free-kassa.png',
  '27' => 'megakassa.png',
  '28' => 'qiwi.png',
];

if (MG::get('templateParams')['FOOTER']['checkbox_paymentShow'] === 'true') : ?>
  <?php
  $res = DB::query(
    'SELECT name, id FROM ' .
      PREFIX .
      'payment WHERE activity=1 ORDER BY id'
  ); ?>

  <ul class="footer__payments">
    <?php while ($payments = DB::fetchAssoc($res)) {
      $imgName = isset($paymentIdToIconName[$payments['id']]) ? $paymentIdToIconName[$payments['id']] : 'cash.png';
    ?>
      <li class="footer__pay payments__item_id_<?php echo $payments['id']; ?>" title="«<?php echo $payments['name']; ?>»">
        <img src="<?php echo SITE . '/mg-admin/design/images/icons/' . $imgName; ?>" alt="«<?php echo $payments['name']; ?>»">
      </li>
    <?php } ?>
  </ul>
<?php endif; ?>