
<div class="game-container">
    <div class="game-header">
        <h1>Jeu de Memory - Retrouve le Parraud</h1>
        <div class="game-info">
            <p><strong>Coups :</strong> <span class="moves-count"><?= $moves ?></span></p>
            <?php if ($game_over): ?>
                <p class="victory-message">🎉 Bravo ! Vous avez gagné en <?= $moves ?> coups !</p>
            <?php endif; ?>
        </div>
        <div class="game-actions">
            <a href="/game?action=reset" class="btn btn-reset">Nouvelle partie</a>
        </div>
    </div>

    <div class="game-board">
        <?php foreach ($deck as $index => $card):
            $isFlipped = in_array($index, $flipped) || in_array($index, $matched);
            $isMatched = in_array($index, $matched);
            $isMissmatch = $missmatch && in_array($index, $flipped) && !$isMatched;

            $cardClass = 'card';
            if ($isFlipped) $cardClass .= ' flipped';
            if ($isMatched) $cardClass .= ' matched';
            if ($isMissmatch) $cardClass .= ' missmatch';
        ?>
            <a href="<?= ($missmatch || $isMatched) ? '#' : '/game?flip=' . $index ?>"
                class="<?= $cardClass ?>"
                data-index="<?= $index ?>">
                <div class="card-inner">
                    <div class="card-front">
                        <img src="<?= htmlspecialchars($card->image_path(), ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($card->name(), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="card-back">
                        <div class="card-back-design">?</div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($missmatch): ?>
    <script>
        setTimeout(function() {
            window.location.href = '/game?action=clear_missmatch';
        }, 1000);
    </script>
<?php endif; ?>

<style>
    .game-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .game-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .game-header h1 {
        color: #333;
        margin-bottom: 20px;
    }

    .game-info {
        margin: 15px 0;
    }

    .moves-count {
        font-size: 1.2em;
        font-weight: bold;
        color: #007bff;
    }

    .victory-message {
        color: #28a745;
        font-size: 1.3em;
        font-weight: bold;
        margin: 10px 0;
    }

    .game-actions {
        margin: 15px 0;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .btn-reset {
        background-color: #6c757d;
    }

    .btn-reset:hover {
        background-color: #5a6268;
    }

    .game-board {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        aspect-ratio: 1;
        perspective: 1000px;
        cursor: pointer;
        text-decoration: none;
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .card.flipped .card-inner {
        transform: rotateY(180deg);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-front {
        transform: rotateY(180deg);
        background: white;
    }

    .card-front img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .card-back {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-back-design {
        font-size: 3em;
        color: white;
        font-weight: bold;
    }

    .card.matched {
        opacity: 0.6;
        cursor: default;
    }

    .card.missmatch .card-inner {
        animation: shake 0.5s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: rotateY(180deg) translateX(0);
        }

        25% {
            transform: rotateY(180deg) translateX(-10px);
        }

        75% {
            transform: rotateY(180deg) translateX(10px);
        }
    }

    @media (max-width: 768px) {
        .game-board {
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
    }
</style>