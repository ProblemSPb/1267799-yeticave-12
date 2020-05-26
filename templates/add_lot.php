
  <form class="form form--add-lot container <?php if (count($errors)) : ?> form--invalid <?php endif; ?>" action="add.php" method="post" enctype="multipart/form-data">
    <!-- form--invalid -->
    <h2>Добавление лота</h2>

    <div class="form__container-two">

      <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : ''; ?>">
        <!-- form__item--invalid -->
        <label for="lot-name">Наименование <sup>*</sup></label>
        <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?= htmlspecialchars(getPostValue('name')); ?>">
        <span class="form__error"><?= $errors['name'] ?? ""; ?></span>
      </div>

      <div class="form__item <?= isset($errors['category']) ? 'form__item--invalid' : ''; ?>">
        <label for="category">Категория <sup>*</sup></label>
        <select id="category" name="category">
          <option>Выберите категорию</option>
          <?php foreach ($categories as $category) : ?>
            <option value=<?= $category['id']; ?> <?= getPostValue('category') == $category['id'] ? "selected" : ""; ?>><?= htmlspecialchars($category['name']); ?></option>
          <?php endforeach; ?>
        </select>
        <span class="form__error"><?= $errors['category'] ?? ""; ?></span>
      </div>

    </div>

    <div class="form__item form__item--wide <?= isset($errors['description']) ? 'form__item--invalid' : ''; ?>">
      <label for="message">Описание <sup>*</sup></label>
      <textarea id="message" name="description" placeholder="Напишите описание лота"><?= htmlspecialchars(getPostValue('description')); ?></textarea>
      <span class="form__error"><?= $errors['description'] ?? "" ?></span>
    </div>

    <div class="form__item form__item--file <?= isset($errors['lot_img']) ? 'form__item--invalid' : ''; ?>">
      <label>Изображение <sup>*</sup></label>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" id="lot_img" name="lot_img">
        <label for="lot_img">
          Добавить
        </label>
        <span class="form__error"><?= $errors['lot_img'] ?? ""; ?></span>
      </div>
    </div>

    <div class="form__container-three">

      <div class="form__item form__item--small <?= isset($errors['start_price']) ? 'form__item--invalid' : ''; ?>">
        <label for="lot-rate">Начальная цена <sup>*</sup></label>
        <input id="lot-rate" type="text" name="start_price" placeholder="0" value="<?= htmlspecialchars(getPostValue('start_price')); ?>">
        <span class="form__error"><?= $errors['start_price'] ?? ""; ?></span>
      </div>

      <div class="form__item form__item--small <?= isset($errors['bid_step']) ? 'form__item--invalid' : ''; ?>">
        <label for="lot-step">Шаг ставки <sup>*</sup></label>
        <input id="lot-step" type="text" name="bid_step" placeholder="0" value="<?= htmlspecialchars(getPostValue('bid_step')); ?>">
        <span class="form__error"><?= $errors['bid_step'] ?? ""; ?></span>
      </div>

      <div class="form__item <?= isset($errors['end_date']) ? 'form__item--invalid' : ''; ?>">
        <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
        <input class="form__input-date" id="lot-date" type="text" name="end_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= htmlspecialchars(getPostValue('end_date')); ?>">
        <span class="form__error"><?= $errors['end_date'] ?? ""; ?></span>
      </div>

    </div>

    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
  </form>