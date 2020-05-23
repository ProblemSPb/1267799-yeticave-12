<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">

    <?php foreach ($bids as $key => $value) : ?>

      <?php
      // получение часов и минут до конца аукциона
      $auc_end_hr = auction_end($value['expire']);
      $hr = $auc_end_hr[0];
      $min = $auc_end_hr[1];

      // класс стиля для выигравшего лота
      $tr_class_rates_timer = trClassRatesTimer($hr, $value['winner'], $user_id);

      // класс стилей для таймера аукциона
      $td_class_timer = tdClassTimer($hr, $value['winner'], $user_id);

      // значение таймера для аукциона
      $timer_value = asignPastBidValue($hr, $min, $value['winner'], $user_id);
      ?>

      <tr class="rates__item <?= $tr_class_rates_timer; ?>">
        <td class="rates__info">
          <div class="rates__img">
            <img src="<?= strip_tags($value['url']); ?>" width="54" height="40" alt="<?= strip_tags($value['name']); ?>">
          </div>
          <div>
          <h3 class="rates__title"><a href="lot.php?id=<?= $value['id'] ?>"><?= strip_tags($value['name']); ?></a></h3>

          <?php if (isWinner($value['winner'], $user_id)) : ?>
            <p><?= strip_tags($value['user_contact']); ?></p>
          <?php endif; ?>
          </div>
        </td>
        <td class="rates__category">
          <?= strip_tags($value['category']); ?>
        </td>

        <td class="rates__timer">
          <div class="timer <?= $td_class_timer; ?>"><?= $timer_value; ?></div>
        </td>
        <td class="rates__price">
          <?= price_format($value['sum_price']); ?>
        </td>
        <td class="rates__time">
          <?= compareDates($value['bid_date']); ?>
        </td>
      </tr>
    <?php endforeach; ?>

  </table>
</section>

<!--


-->