@extends('layouts.app')

@section('content')
    <form action="/save" method="post">
        @csrf
        <input type="hidden" name="game_id" value="{{ $game['id'] }}">
        <div class="karsilasma font-weight-bold">
            <div class="row" style="margin:0">
                <div class="col-md-4">
                    {{ $clubs[$game['home_club_id']-1]['name'] }}
                </div>
                <div class="col-md-4" style="text-align:center;">
                    <div class="row">
                        <div class="col-md-5 text-right">
                            <input type="text" name="home_club_goals" value="{{ $game['home_club_goals'] }}" style="width: 100%;text-align: center;">
                        </div>
                        <div class="col-md-2" style="padding: 0">
                            -
                        </div>
                        <div class="col-md-5 text-left">
                            <input type="text" name="away_club_goals" value="{{ $game['away_club_goals'] }}" style="width: 100%;text-align: center;">
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="text-align:right;">
                    {{ $clubs[$game['away_club_id']-1]['name'] }}
                </div>
            </div>
        </div>
        <div class="text-center">
            <input type="submit" class="karsilasma" value="Save Game" style="width: 256px;">
        </div>
    </form>
@endsection
