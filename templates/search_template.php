<div class="container">
  <section class="lots">
    <h2>Результаты поиска по запросу «<span><?= htmlspecialchars(getGetValue('search')); ?></span>»</h2>


    <ul class="lots__list">
      <!--список из массива с товарами-->
      <?php foreach ($lots as $key => $value) : ?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?= strip_tags($value['url']); ?>" width="350" height="260" alt="">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?= htmlspecialchars($value['category']); ?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $value['id'] ?>"><?= strip_tags($value['name']); ?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?= htmlspecialchars(price_format($value['price'])); ?></span>
              </div>
              <!-- Вызов функции по расчету, сколько часов и минут до конца аукциона-->
              <?php
              $auc_end_hr = auction_end(strip_tags($value['expire']));

              // если осталось меньше часа, то будет выделено красным
              // добавление блоку класса timer--finishing
              $timer_finishing = "";
              if ($auc_end_hr[0] < 1) {
                  $timer_finishing = "timer--finishing";
              }
              ?>
              <div class="lot__timer timer <?= $timer_finishing; ?>">
                <?php echo($auc_end_hr[0] . ":" . $auc_end_hr[1]); ?>
              </div>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

  </section>
  <ul class="pagination-list">

    <!-- показывают ссылку НАЗАД, если есть товары на предыдущих страницах -->
    <!-- если товаров нет, то ссылка скрывается  -->
    <li class="pagination-item pagination-item-prev"><a href="search.php?search=<?= $search; ?>&page=<?= ($page - 1); ?>"><?= ($page === 1) ? "" : "Назад" ; ?></a></li>
    
    <li class="pagination-item pagination-item-active"><a href="search.php?search=<?= $search; ?>&page=<?= ($page); ?>"><?= $page; ?></a></li>

    <!-- следующие три блока показывают ссылки на следующие страницы, если есть товары для отображения -->
    <!-- если товаров в очереди нет, то ссылки скрываются  -->
    <?php if (($page) < $pages_total) : ?>
    <li class="pagination-item"><a href="search.php?search=<?= $search; ?>&page=<?= ($page + 1); ?>"><?= $page + 1; ?></a></li>
    <?php else : ?>
    <li class="pagination-item"><a></a></li>
    <?php endif; ?>

    <?php if (($page+1) < $pages_total) : ?>
    <li class="pagination-item"><a href="search.php?search=<?= $search; ?>&page=<?= ($page + 2); ?>"><?= $page + 2; ?></a></li>
    <?php else : ?>
    <li class="pagination-item"><a></a></li>
    <?php endif; ?>

    <?php if (($page+2) < $pages_total) : ?>
    <li class="pagination-item"><a href="search.php?search=<?= $search; ?>&page=<?= ($page + 3); ?>"><?= $page + 3; ?></a></li>
    <?php else : ?>
    <li class="pagination-item"><a></a></li>
    <?php endif; ?>
    
    <!-- показывает ссылку ВПЕРЕД, если есть товары на следующих страницах -->
    <!-- если товаров нет, то ссылка скрывается  -->
    <li class="pagination-item pagination-item-next"><a href="search.php?search=<?= $search; ?>&page=<?= ($page + 1); ?>"><?= ($page < $pages_total) ? "Вперед" : "" ; ?></a></li>
    
  </ul>
</div>