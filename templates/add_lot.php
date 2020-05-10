<form class="form form--add-lot container form--invalid" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item form__item--invalid"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" required minlength='4' maxlength="100" oninvalid="this.setCustomValidity('Напишите наименование Вашего товара')" oninput="setCustomValidity('')">
          <span class="form__error">Введите наименование лота</span>
        </div>
        <div class="form__item">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach($categories as $category) : ?>
                  <option><?= $category['name']; ?></option>
                <?php endforeach; ?>
          </select>
          <span class="form__error">Выберите категорию</span>
        </div>
      </div>
      <div class="form__item form__item--wide">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="description" placeholder="Напишите описание лота" required minlength='10' maxlength="1000" oninvalid="this.setCustomValidity('Напишите описание Вашего товара')" oninput="setCustomValidity('')"></textarea>
        <span class="form__error">Напишите описание лота</span>
      </div>
      <div class="form__item form__item--file">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="lot_img">
          <label for="lot-img">
            Добавить
          </label>
        </div>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="number" name="start_price" placeholder="0" required oninvalid="this.setCustomValidity('Установите стартовую цену для Вашего товара в числовом формате')" oninput="setCustomValidity('')">
          <span class="form__error">Введите начальную цену</span>
        </div>
        <div class="form__item form__item--small">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="bid_step" placeholder="0" required oninvalid="this.setCustomValidity('Укажите шаг ставки в числовом формате')" oninput="setCustomValidity('')">
          <span class="form__error">Введите шаг ставки</span>
        </div>
        <div class="form__item">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="end_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" required oninvalid="this.setCustomValidity('Укажите дату окончания аукциона для товара')" oninput="setCustomValidity('')">
          <span class="form__error">Введите дату завершения торгов</span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>