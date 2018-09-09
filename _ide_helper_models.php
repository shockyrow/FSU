<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Club
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Game[] $away_games
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Game[] $home_games
 */
	class Club extends \Eloquent {}
}

namespace App{
/**
 * App\Game
 *
 * @property-read \App\Club $away_club
 * @property-read \App\Club $home_club
 */
	class Game extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 */
	class User extends \Eloquent {}
}

