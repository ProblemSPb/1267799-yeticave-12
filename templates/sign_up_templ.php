
    <form class="form container <?php if (count($errors)) : ?> form--invalid <?php endif;?>" action="sign_up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '';?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars(getPostValue('email')); ?>">
        <span class="form__error"><?=$errors['email'] ?? ""; ?></span>
      </div>
      <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '';?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= htmlspecialchars(getPostValue('password')); ?>">
        <span class="form__error"><?=$errors['password'] ?? ""; ?></span>
      </div>
      <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '';?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars(getPostValue('name')); ?>">
        <span class="form__error"><?=$errors['name'] ?? ""; ?></span>
      </div>
      <div class="form__item <?= isset($errors['user_contact']) ? 'form__item--invalid' : '';?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="user_contact" placeholder="Напишите как с вами связаться"><?= htmlspecialchars(getPostValue('user_contact')); ?></textarea>
        <span class="form__error"><?=$errors['user_contact'] ?? ""; ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>