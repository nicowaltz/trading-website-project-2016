
<?php if (! $exists): ?>

  <h1>Diese Aktie gibt es nicht</h1>

<?php else: ?>

  <div class="detail-group">
    <h3><?= $symbol ?></h3>
    <div class="detail-container"><span class="title">Unternehmen: </span><span class="info"><?= $name ?></span></div>
    <div class="detail-container"><span class="title">Einzelwert: </span><span class="info"><?= num($price) . ' €' ?></span></div>
    <div class="detail-container"><span class="title">Veränderung: </span><span class="info change-<?= $csgn ?>"><?= $csgn === 'positive' ? '+' . num($change) : num($change) ?></span></div>

    <form class="buy-form" action="/buy" method="post" autocomplete="off" autocapitalize="off">
      <input type="hidden" name="stock" value="<?= $symbol ?>">
      <input type="text" name="amount" id="amount" placeholder="Anzahl">

      <button type="submit" class="button" name="button">Kaufen</button>
    </form>

  </div>

  <div class="detail-group">
    <img src="<?= $charturl ?>" alt="Grafik" />
  </div>
<?php endif ?>
