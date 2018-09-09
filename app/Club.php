<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Club
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Game[] $away_games
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Game[] $home_games
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $offensive_power
 * @property int $defensive_power
 * @property int $fan_power
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Club whereDefensivePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Club whereFanPower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Club whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Club whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Club whereOffensivePower($value)
 */
class Club extends Model
{
    protected $fillable = [
        'name',
        'offensive_power',
        'defensive_power',
        'fan_power'
    ];

    public $timestamps = false;

    public function home_games() {
        return $this->hasMany('App\Game', 'home_club_id');
    }

    public function away_games() {
        return $this->hasMany('App\Game', 'away_club_id');
    }

    public function totalPower($isHome) {
        if ($isHome)
            return $this->offensive_power+$this->defensive_power+$this->fan_power+50;
        return $this->offensive_power+$this->defensive_power+$this->fan_power;
    }

    public function attackPower($isHome) {
        if ($isHome)
            return $this->offensive_power+$this->fan_power+50;
        return $this->offensive_power+$this->fan_power;
    }

    public function defendPower($isHome) {
        if ($isHome)
            return $this->defensive_power+$this->fan_power+50;
        return $this->defensive_power+$this->fan_power;
    }

    public function countPlayed() {
        return $this->home_games->where('is_played', true)->count()+$this->away_games->where('is_played', true)->count();
    }

    public function countWins() {
        $result = 0;
        $games = $this->home_games;
        foreach ($games as $game) {
            if($game->is_played)
                if($game->home_club_goals>$game->away_club_goals)
                    $result++;
        }
        $games = $this->away_games;
        foreach ($games as $game) {
            if($game->is_played)
                if($game->away_club_goals>$game->home_club_goals)
                    $result++;
        }
        return $result;
    }

    public function countDraws() {
        $result = 0;
        $games = $this->home_games;
        foreach ($games as $game) {
            if($game->is_played)
                if($game->home_club_goals==$game->away_club_goals)
                    $result++;
        }
        $games = $this->away_games;
        foreach ($games as $game) {
            if($game->is_played)
                if($game->away_club_goals==$game->home_club_goals)
                    $result++;
        }
        return $result;
    }

    public function countLoses() {
        $result = 0;
        $games = $this->home_games;
        foreach ($games as $game) {
            if($game->is_played)
                if($game->home_club_goals<$game->away_club_goals)
                    $result++;
        }
        $games = $this->away_games;
        foreach ($games as $game) {
            if($game->is_played)
                if($game->away_club_goals<$game->home_club_goals)
                    $result++;
        }
        return $result;
    }

    public function countGF() {
        $result = 0;
        $games = $this->home_games;
        foreach ($games as $game) {
            if($game->is_played)
                $result+=$game->home_club_goals;
        }
        $games = $this->away_games;
        foreach ($games as $game) {
            if($game->is_played)
                $result+=$game->away_club_goals;
        }
        return $result;
    }

    public function countGA() {
        $result = 0;
        $games = $this->home_games;
        foreach ($games as $game) {
            if($game->is_played)
                $result+=$game->away_club_goals;
        }
        $games = $this->away_games;
        foreach ($games as $game) {
            if($game->is_played)
                $result+=$game->home_club_goals;
        }
        return $result;
    }

    public function countGD() {
        return $this->countGF()-$this->countGA();
    }

    public function countPoints() {
        $result = 0;
        $games = $this->home_games;
        foreach ($games as $game) {
            if($game->is_played) {
                if($game->home_club_goals>$game->away_club_goals)
                    $result+=3;
                else if($game->home_club_goals==$game->away_club_goals)
                    $result++;
            }

        }
        $games = $this->away_games;
        foreach ($games as $game) {
            if($game->is_played) {
                if ($game->away_club_goals > $game->home_club_goals)
                    $result += 3;
                else if ($game->away_club_goals == $game->home_club_goals)
                    $result++;
            }
        }
        return $result;
    }
}
