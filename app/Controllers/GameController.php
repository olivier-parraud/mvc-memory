<?php

namespace App\Controllers;

use Core\BaseController;

class Card
{
    public function __construct(
        private readonly string $slug,
        private readonly string $label,
        private readonly string $image_path
    ) {}

    public function slug(): string
    {
        return $this->slug;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function image_path(): string
    {
        return $this->image_path;
    }

    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'label' => $this->label,
            'image' => $this->image_path,
        ];
    }
}


class Game extends BaseController
{
    private array $deck;
    private array $flipped;
    private array $matched;
    private int $moves;
    private bool $gameOver;

    public function __construct()
    {
        $this->init_from_session();
    }

    private function init_from_session(): void
    {
        if (!isset($_SESSION['deck'])) {
            $this->create_new_game();
        } else {
            $this->deck = $_SESSION['deck'];
            $this->flipped = $_SESSION['flipped'] ?? [];
            $this->matched = $_SESSION['matched'] ?? [];
            $this->moves = $_SESSION['moves'] ?? 0;
            $this->gameOver = $_SESSION['game_over'] ?? false;
        }
    }

    public function create_new_game(): void
    {
        $card1 = new Card('astronaut', 'Astronaute', '/assets/img/astronaut.jpg');
        $card2 = new Card('compass', 'Boussole', '/assets/img/compass.jpg');
        $card3 = new Card('lotus', 'Lotus', '/assets/img/lotus.jpg');
        $card4 = new Card('kite', 'Cerf-volant', '/assets/img/kite.jpg');
        $card5 = new Card('lighthouse', 'Phare', '/assets/img/lighthouse.jpg');
        $card6 = new Card('mask', 'Masque', '/assets/img/mask.jpg');
        $card7 = new Card('meteor', 'Météore', '/assets/img/meteor.jpg');
        $card8 = new Card('origami-crane', 'Grue Origami', '/assets/img/origami.jpg');
        $card9 = new Card('pearl', 'Perle', '/assets/img/pearl.jpg');
        $card10 = new Card('tea-cup', 'Tasse de thé', '/assets/img/tea-cup.jpg');

        $cards = [$card1, $card2, $card3, $card4, $card5, $card6, $card7, $card8, $card9, $card10];

        $deck = [];
        foreach ($cards as $card) {
            $deck[] = $card;
            $deck[] = $card;
        }
        shuffle($deck);

        $this->deck = $deck;
        $this->flipped = [];
        $this->matched = [];
        $this->moves = 0;
        $this->gameOver = false;

        $this->save_to_session();
    }

    public function flipCard(int $index): void
    {
        // Vérifier que l'index est valide et que la carte n'est pas déjà trouvée ou retournée
        if (!isset($this->deck[$index]) || in_array($index, $this->matched) || in_array($index, $this->flipped)) {
            return;
        }

        // Si on a déjà 2 cartes retournées (tour précédent fini mais non match), on recommence un tour
        if (count($this->flipped) >= 2) {
            $this->flipped = [];
        }

        // On retourne la carte choisie
        $this->flipped[] = $index;

        // Si on a maintenant 2 cartes retournées, on vérifie si elles matchent
        if (count($this->flipped) === 2) {
            $this->moves++;
            $idx1 = $this->flipped[0];
            $idx2 = $this->flipped[1];

            if ($this->deck[$idx1]->slug() === $this->deck[$idx2]->slug()) {
                // C'est une paire !
                $this->matched[] = $idx1;
                $this->matched[] = $idx2;
                $this->flipped = [];

                // Vérifier la fin de partie
                if (count($this->matched) === count($this->deck)) {
                    $this->gameOver = true;
                }
            }
        }

        $this->save_to_session();
    }

    public function clear_missmatch(): void
    {
        $this->flipped = [];
        $this->save_to_session();
    }

    public function reset(): void
    {
        unset($_SESSION['deck']);
        unset($_SESSION['flipped']);
        unset($_SESSION['matched']);
        unset($_SESSION['moves']);
        unset($_SESSION['game_over']);
        $this->create_new_game();
    }

    private function save_to_session(): void
    {
        $_SESSION['deck'] = $this->deck;
        $_SESSION['flipped'] = $this->flipped;
        $_SESSION['matched'] = $this->matched;
        $_SESSION['moves'] = $this->moves;
        $_SESSION['game_over'] = $this->gameOver;
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function getFlipped(): array
    {
        return $this->flipped;
    }

    public function getMatched(): array
    {
        return $this->matched;
    }

    public function getMoves(): int
    {
        return $this->moves;
    }

    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    public function hasMismatch(): bool
    {
        return count($this->flipped) === 2;
    }
}
