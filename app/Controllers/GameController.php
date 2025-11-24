<?php

namespace App\Controllers;

use Core\BaseController;


class GameController extends BaseController
{
    private array $deck;
    private array $flipped;
    private array $matched;
    private int $moves;
    private bool $game_over;

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
            $this->game_over = $_SESSION['game_over'] ?? false;
        }
    }

    public function index(): void
    {
        session_start();


        $data = [
            'title' => 'Jeu de Memory',
            'deck' => $this->get_deck(),
            'flipped' => $this->get_flipped(),
            'matched' => $this->get_matched(),
            'moves' => $this->get_moves(),
            'missmatch' => $this->has_missmatch(),
            'game_over' => $this->is_game_over()
        ];

        $this->render('game/index', $data);
    }


    public function create_new_game(): void
    {
        $card1 = new Card('ol_bebe', '/assets/img/ol_moustache.jpg');
        $card2 = new Card('ol_chapeau', '/assets/img/ol_casquette.jpg');
        $card3 = new Card('ol_chat', '/assets/img/ol_lunette.jpg');
        $card4 = new Card('ol_alien', '/assets/img/ol_alien.jpg');
        $card5 = new Card('ol_cyborg', '/assets/img/ol_chapeau.jpg');
        $card6 = new Card('ol_dark', '/assets/img/ol_femme.jpg');
        $card7 = new Card('ol_emo', '/assets/img/ol_chat.jpg');
        $card8 = new Card('ol_squelette', '/assets/img/ol_cyborg.jpg');
        $card9 = new Card('ol_vieux', '/assets/img/ol_vieux.jpg');
        $card10 = new Card('ol_emo', '/assets/img/ol_emo.jpg');



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
        $this->game_over = false;

        $this->save_to_session();
    }

    public function flip(int $index): void
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

            if ($this->deck[$idx1]->name() === $this->deck[$idx2]->name()) {
                // C'est une paire !
                $this->matched[] = $idx1;
                $this->matched[] = $idx2;
                $this->flipped = [];

                // Vérifier la fin de partie
                if (count($this->matched) === count($this->deck)) {
                    $this->game_over = true;
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
        $_SESSION['game_over'] = $this->game_over;
    }

    public function get_deck(): array
    {
        return $this->deck;
    }

    public function get_flipped(): array
    {
        return $this->flipped;
    }

    public function get_matched(): array
    {
        return $this->matched;
    }

    public function get_moves(): int
    {
        return $this->moves;
    }

    public function is_game_over(): bool
    {
        return $this->game_over;
    }

    public function has_missmatch(): bool
    {
        return count($this->flipped) === 2;
    }

    public function get_cover_card(): Card
    {
        return new Card('cover', '/assets/img/cover.jpg');
    }
}

class Card
{
    public function __construct(
        private readonly string $name,
        private readonly string $image_path
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function image_path(): string
    {
        return $this->image_path;
    }

    public function array(): array
    {
        return [
            'name' => $this->name,
            'image' => $this->image_path,
        ];
    }
}
