<div class="portfolio">
  <nav id="nav">
    <ul class="nav-list">
      <li class="left">
        <div class="user"><?= $user . ': '?><span class="user-cash"><?= num($cash) . ' €' ?></span></div>
      </li>
      <li class="right"><a class="button positive" href="/logout">Abmelden</a></li>
    </ul>
  </nav>
  <main id="main">
    <table class="portfolio-table" cellspacing=0>
      <thead>
        <tr>
          <th>Ticker</th>
          <th>Unternehmen</th>
          <th>Veränderung</th>
          <th>Momentaner Preis</th>
          <th>Aktien</th>
          <th>Gekauft für</th>
          <th>Verkaufspreis</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($holdings as $holding): ?>
          <tr>
            <td class="d">

              <?= $holding['symbol'] ?>
              <div class="details">
                <div class="detail-group">
                  <h3><?= $holding['symbol'] ?></h3>
                  <div class="detail-container"><span class="title">Unternehmen: </span><span class="info"><?= $holding['name'] ?></span></div>
                  <div class="detail-container"><span class="title">Einzelwert: </span><span class="info"><?= num($holding['price']) . ' €' ?></span></div>
                  <div class="detail-container"><span class="title">Veränderung: </span><span class="info change-<?= $holding['change-sgn'] ?>"><?= ($holding['change-sgn'] === 'positive') ? '+' . num($holding['change']) : num($holding['change']) ?></span></div>
                  <div class="detail-container"><span class="title">Kaufpreis: </span><span class="info"><?= num($holding['initial_payment']) . ' €' ?></span></div>
                  <div class="detail-container"><span class="title">Verkaufspreis: </span><span class="info"><?= num($holding['total']) . ' €' ?></span></div>
                  <div class="detail-container"><span class="title">Differenz: </span><span class="info difference-<?= $holding['difference-sgn'] ?>"><?= (($holding['difference-sgn'] === 'positive') ? '+' . num($holding['difference']) : num($holding['difference'])) . ' €' ?></span></div>

                  <a href="/sell?s=<?= $holding['symbol'] ?>&amp;_=<?= $uniqid ?>" class="button">Verkaufen</a>
                </div>
                <div class="detail-group">

                  <img src="<?= $holding['charturl'] ?>" alt="Aktienkurs" />
                </div>
                <span class="close">&times;</span>
              </div>
            </td>
            <td><?= $holding['name'] ?></td>
            <td class="change-<?= $holding['change-sgn'] ?>">
              <?= ($holding['change-sgn'] === 'positive') ? '+' . num($holding['change']) : num($holding['change']) ?>
            </td>
            <td><?= '€ ' . num($holding['price']) ?></td>
            <td><?= $holding['shares'] ?></td>
            <td><?= '€ ' . num($holding['initial_payment']) ?></td>
            <td><?= '€ ' . num($holding['total']) ?></td>
          </tr>
        <?php endforeach ?>
        <tr class="buy-tr">
          <td colspan=7 class="buy-td">
            <div class="buy">
              <a class="buy-button" href="/buy">Kaufen</a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </main>
</div>
