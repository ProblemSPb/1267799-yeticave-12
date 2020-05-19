<section class="lot-item container">
    <h2><?= $lot['name']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src=<?= $lot['img_link']; ?> width="730" height="548" alt=<?= $lot['category name']; ?>>
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category name']; ?></span></p>
            <p class="lot-item__description"><?= $lot['description']; ?></p>
        </div>

        <div class="lot-item__right">

            <div class="lot-item__state">
                
                <!-- Вызов функции по расчету, сколько часов и минут до конца аукциона-->
                <?php 
                $auc_end_hr = auction_end($lot['end_date']);
                
                // если осталось меньше часа, то будет выделено красным
                // добавление блоку класса timer--finishing
                $timer_finishing = "";
                if($auc_end_hr[0] < 1) {
                $timer_finishing = "timer--finishing";
                }
                ?>
            <div class="lot-item__timer timer <?= $timer_finishing; ?>">
            <?php echo($auc_end_hr[0].":".$auc_end_hr[1]); ?>
            </div>

                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= price_format($lot['start_price']); ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= price_format($lot['bid_step']); ?></span>
                    </div>
                </div>
                <?php if (isset($_SESSION['user'])) : ?>
                <form class="lot-item__form" action="lot.php?id=<?= $lot['id']; ?>" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item <?php if (count($errors)) : ?> form__item--invalid <?php endif; ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="<?= price_format($lot['bid_step']); ?>">
                        <span class="form__error"><?= $errors['cost'] ?? ""; ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
                <?php endif; ?>
            </div>

            <div class="history">
                <h3>История ставок (<span>10</span>)</h3>
                <table class="history__list">
                    <tr class="history__item">
                        <td class="history__name">Иван</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">5 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Константин</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">20 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Евгений</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">Час назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Игорь</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 08:21</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
