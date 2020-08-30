<div class="loginpage">
	<form action="/login" method="post" autocomplete="off">
		<div class="input-group">
			<input type="text" name="username" <?php if(isset($errors) && $errors->has('username')): ?>class="error" onfocusin="this.className=''" <?php endif ?> placeholder="Benutzer" <?php if(isset($username)): ?>value="<?= $username ?>"<?php endif ?>>
			<?php if(isset($errors) && $errors->has('username')): ?>
				<div class="error"><?= $errors->first('username') ?></div>
			<?php endif ?>
		</div>
		<div class="input-group">
			<input type="password" name="password" <?php if(isset($errors) && $errors->has('password') && !$errors->has('username')): ?>class="error" onfocusin="this.className=''" <?php endif ?> placeholder="Passwort">
			<?php if(isset($errors) && $errors->has('password') && !$errors->has('username')): ?>
				<div class="error"><?= $errors->first('password') ?></div>
			<?php endif ?>
		</div>
		<button type="submit" class="button positive">Anmelden</button>
	</form>
</div>