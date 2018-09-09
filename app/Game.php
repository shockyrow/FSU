<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Game
 *
 * @property-read \App\Club $away_club
 * @property-read \App\Club $home_club
 * @mixin \Eloquent
 * @property int $id
 * @property int $week
 * @property int $home_club_id
 * @property int $away_club_id
 * @property int $home_club_goals
 * @property int $away_club_goals
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereAwayClubGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereAwayClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereHomeClubGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereHomeClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Game whereWeek($value)
 */
class Game extends Model
{
    protected $fillable = [
        'week',
        'is_played',
        'home_club_id',
        'away_club_id',
        'home_club_goals',
        'away_club_goals'
    ];

    public $timestamps = false;

    public function home_club() {
        return $this->belongsTo(Club::class);
    }

    public function away_club() {
        return $this->belongsTo(Club::class);
    }
}
