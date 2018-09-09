<?php

namespace App\Http\Controllers;

use App\Club;
use App\Game;
use function foo\func;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class LeagueController extends Controller
{
    private $current_week = 1;

    public function home() {
        $clubs = Club::all();
        $rankings = $clubs->toArray();
        for ($i = 0; $i < count($rankings); $i++) {
            $rankings[$i]['played'] = $clubs[$i]->countPlayed();
            $rankings[$i]['wins'] = $clubs[$i]->countWins();
            $rankings[$i]['draws'] = $clubs[$i]->countDraws();
            $rankings[$i]['loses'] = $clubs[$i]->countLoses();
            $rankings[$i]['gf'] = $clubs[$i]->countGF();
            $rankings[$i]['ga'] = $clubs[$i]->countGA();
            $rankings[$i]['gd'] = $clubs[$i]->countGD();
            $rankings[$i]['points'] = $clubs[$i]->countPoints();
        }
        usort($rankings, function($a, $b){
            if($a['points']==$b['points'])
                return $a['gd']<$b['gd'];
            return $a['points']<$b['points'];
        });
        $week_games = Game::where('week', Club::first()->countPlayed())->get()->toArray();
        if(Game::where('is_played',false)->count()<=Club::count()) {
            $guesses = [];
            foreach ($clubs as $club) {
                array_push($guesses, [
                    'name' => $club->name,
                    'count' => 0
                ]);
            }
            $games = Game::where('is_played', false)->get()->toArray();
            $this->guessWinner($games, $guesses, 0);
            $clubs = $clubs->toArray();
            return view('home', ['clubs_ranking' => $rankings, 'week_games' => $week_games, 'clubs' => $clubs, 'guesses' => $guesses]);
        }
        $clubs = $clubs->toArray();
        return view('home', ['clubs_ranking' => $rankings, 'week_games' => $week_games, 'clubs' => $clubs]);
    }

    public function edit($id) {
        return view('edit', ['game' => Game::find($id), 'clubs' => Club::all()->toArray()]);
    }

    public function save(Request $request) {
        $data = $request->all();
        $game = Game::find($data['game_id']);
        if ($game!=null) {
            $game->home_club_goals = $data['home_club_goals'];
            $game->away_club_goals = $data['away_club_goals'];
            $game->save();
        }
        return redirect('/games');
    }

    public function playedGames() {
        $games = Game::where('is_played', true)->get()->toArray();
        $clubs = Club::all()->toArray();
        return view('games', ['clubs' => $clubs, 'games' => $games]);
    }

    public function firstGame() {
        return Club::find(1)->away_games->toArray();
    }

    public function refresh() {
        $this->current_week = 1;
        DB::statement("SET foreign_key_checks = 0");
        Club::truncate();
        Game::truncate();
        DB::statement("SET foreign_key_checks = 1");
        $clubs = [
            [
                'name' => 'Fenerbahçe',
                'offensive_power' => random_int(85,100), // Fenerliyim ben ;)
                'defensive_power' => random_int(85,100), // Fenerliyim ben ;)
                'fan_power' => random_int(85,100) // Fenerliyim ben ;)
            ],
            [
                'name' => 'Galatasaray',
                'offensive_power' => random_int(50,100),
                'defensive_power' => random_int(50,100),
                'fan_power' => random_int(50,100)
            ],
            [
                'name' => 'Beşiktaş',
                'offensive_power' => random_int(50,100),
                'defensive_power' => random_int(50,100),
                'fan_power' => random_int(50,100)
            ],
            [
                'name' => 'Sivas Spor',
                'offensive_power' => random_int(50,100),
                'defensive_power' => random_int(50,100),
                'fan_power' => random_int(50,100)
            ]
        ];
        try {
            Club::insert($clubs);
        } catch (QueryException $e) {
            // nothing
        }
        $this->createFixture();
        return redirect('/home');
    }

    public function createFixture() {
        $clubs = Club::all()->toArray();
        $static_club = array_shift($clubs);
        $n = Club::count();
        for ($i = 1; $i < $n; $i++) {
            $this->createGame($static_club, $clubs[0], $i);
            for ($j = 1; $j < $n/2; $j++) {
                $this->createGame($clubs[$j], $clubs[$n-$j-1], $i);
            }
            $tmp = array_shift($clubs);
            array_push($clubs, $tmp);
        }
        for ($i = 1; $i < $n; $i++) {
            $this->createGame($clubs[0], $static_club, $i+$n-1);
            for ($j = 1; $j < $n/2; $j++) {
                $this->createGame($clubs[$n-$j-1], $clubs[$j], $i+$n-1);
            }
            $tmp = array_shift($clubs);
            array_push($clubs, $tmp);
        }
    }

    public function createGame($home, $away, $week) {
        $newGame = [
            'week' => $week,
            'home_club_id' => $home['id'],
            'away_club_id' => $away['id'],
            'home_club_goals' => 0,
            'away_club_goals' => 0
        ];
        try {
            Game::create($newGame);
        } catch (QueryException $e) {
            // do nothing
        }
    }

    public function playGame($game_id) {
        $game = Game::find($game_id);
        $maxPower = $game->home_club->totalPower(true)+$game->away_club->totalPower(false);
        $position_count = random_int(0,10);
        for ($i = 0; $i <= $position_count; $i++) {
            $result1 = random_int(1, $maxPower);
            if ($result1 <= $game->home_club->totalPower(true)) {
                $result2 = random_int(1, $game->home_club->attackPower(true)+$game->away_club->defendPower(false));
                if($result2 <= $game->home_club->attackPower(true)) {
                    $game->home_club_goals++;
                }
            }
            else {
                $result2 = random_int(1, $game->home_club->defendPower(true)+$game->away_club->attackPower(false));
                if($result2 > $game->home_club->defendPower(true)) {
                    $game->away_club_goals++;
                }
            }
        }
        $game->is_played = true;
        $game->save();
    }

    public function fakePlay($game_id, $home_goals, $away_goals) {
        $game = Game::find($game_id);
        $game->home_club_goals = $home_goals;
        $game->away_club_goals = $away_goals;
        $game->is_played = true;
        $game->save();
    }

    public function finishWeek() {
        $week_games = Game::where('is_played', false)->take(Club::count()/2)->get();
        if ($week_games->count() > 0) {
            foreach ($week_games as $game) {
                $this->playGame($game->id);
            }
        }
        return redirect('/home');
    }

    public function finishLeague() {
        $week_games = Game::where('is_played', false)->get();
        foreach ($week_games as $game) {
            $this->playGame($game->id);
        }
        return redirect('/home');
    }

    public function guessWinner($games, &$result, $index) {
        if ($index==count($games)) {
            $clubs = Club::all();
            $rankings = $clubs->toArray();
            for ($i = 0; $i < count($rankings); $i++) {
                $rankings[$i]['played'] = $clubs[$i]->countPlayed();
                $rankings[$i]['wins'] = $clubs[$i]->countWins();
                $rankings[$i]['draws'] = $clubs[$i]->countDraws();
                $rankings[$i]['loses'] = $clubs[$i]->countLoses();
                $rankings[$i]['gf'] = $clubs[$i]->countGF();
                $rankings[$i]['ga'] = $clubs[$i]->countGA();
                $rankings[$i]['gd'] = $clubs[$i]->countGD();
                $rankings[$i]['points'] = $clubs[$i]->countPoints();
            }
            usort($rankings, function($a, $b){
                if($a['points']==$b['points'])
                    return $a['gd']<$b['gd'];
                return $a['points']<$b['points'];
            });
            $result[$rankings[0]['id']-1]['count']++;
            return;
        }
        $game = Game::find($games[$index]['id']);
        $this->fakePlay($game->id, 0, 0);
        $this->guessWinner($games, $result, $index+1);
        $this->fakePlay($game->id, 1, 0);
        $this->guessWinner($games, $result, $index+1);
        $this->fakePlay($game->id, 0, 1);
        $this->guessWinner($games, $result, $index+1);
        $game->is_played = false;
        $game->save();
        return;
    }
}
