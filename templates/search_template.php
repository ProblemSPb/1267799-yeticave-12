<div class="container">
  <section class="lots">
    <h2>Результаты поиска по запросу «<span><?= getGetValue('search'); ?></span>»</h2>


    <ul class="lots__list">
      <!--список из массива с товарами-->
      <?php foreach ($lots as $key => $value) : ?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?= strip_tags($value['url']); ?>" width="350" height="260" alt="">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?= $value['category']; ?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $value['id'] ?>"><?= strip_tags($value['name']); ?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?= price_format($value['price']); ?></span>
              </div>
              <!-- Вызов функции по расчету, сколько часов и минут до конца аукциона-->
              <?php
              $auc_end_hr = auction_end($value['expire']);

              // если осталось меньше часа, то будет выделено красным
              // добавление блоку класса timer--finishing
              $timer_finishing = "";
              if ($auc_end_hr[0] < 1) {
                $timer_finishing = "timer--finishing";
              }
              ?>
              <div class="lot__timer timer <?= $timer_finishing; ?>">
                <?php echo ($auc_end_hr[0] . ":" . $auc_end_hr[1]); ?>
              </div>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

  </section>
  <ul class="pagination-list">
    <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
    <li class="pagination-item pagination-item-active"><a>1</a></li>
    <li class="pagination-item"><a href="#">2</a></li>
    <li class="pagination-item"><a href="#">3</a></li>
    <li class="pagination-item"><a href="#">4</a></li>
    <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
  </ul>
</div>