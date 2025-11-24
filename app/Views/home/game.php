
<head>
    <title>Retrouve le Parraud</title>
    <link rel="stylesheet" type="text/css" href="assets/styles.css">
    <?php if ($mismatch): ?>
        <meta http-equiv="refresh" content="1;url=index.php?action=clear_mismatch">
    <?php endif; ?>
</head>

<body>
    <h1>Jeu de memory</h1>
    <h2>Narcisse Industries</h2>

    <div class="game-header">
        <div class="player-info">
            <strong>Joueur :</strong> <span class="highlight"><?= htmlspecialchars($username) ?></span>
            <span class="separator">|</span>
            <strong>Coups :</strong> <span class="highlight"><?= $moves ?></span>
        </div>
        <div class="game-actions">
            <a href="?action=reset" class="btn">Nouvelle partie</a>
            <a href="?logout=1" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>

    <div class="game-board" id="game">
        <?php foreach ($deck as $index => $card):
            $isFlipped = in_array($index, $flipped) || in_array($index, $matched);
            $isMismatchCard = $mismatch && in_array($index, $flipped);

            $class = 'card';
            if ($isFlipped) $class .= ' flipped';
            if ($isMismatchCard) $class .= ' shake';
        ?>
            <a href="<?= $mismatch ? '#' : '?flip=' . $index ?>" id="card-<?= $index ?>" class="<?= $class ?>" data-slug="<?= htmlspecialchars($card->slug(), ENT_QUOTES, 'UTF-8') ?>">
                <img class="card-face" src="<?= htmlspecialchars($card->imagePath(), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($card->label(), ENT_QUOTES, 'UTF-8') ?>">
                <img class="card-back" src="<?= htmlspecialchars($coverCard->imagePath(), ENT_QUOTES, 'UTF-8') ?>" alt="Dos de carte">
            </a>
        <?php endforeach; ?>
    </div>
</body>

