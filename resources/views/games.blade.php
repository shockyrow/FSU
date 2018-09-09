@extends('layouts.app')

@section('content')
    @forelse ($games as $game)
        <div class="karsilasma font-weight-bold">
            <div class="row" style="margin:0">
                <div class="col-md-4">
                    {{ $clubs[$game['home_club_id']-1]['name'] }}
                </div>
                <div class="col-md-4" style="text-align:center;">
                    <div class="row">
                        <div class="col-md-5 text-right">
                            {{ $game['home_club_goals'] }}
                        </div>
                        <div class="col-md-2" style="padding: 0">
                            <a href="/edit/{{ $game['id'] }}">
                                <img src="{{ asset('img/edit.png') }}" width="16" style="margin-bottom: 6px;">
                            </a>
                        </div>
                        <div class="col-md-5 text-left">
                            {{ $game['away_club_goals'] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="text-align:right;">
                    {{ $clubs[$game['away_club_id']-1]['name'] }}
                </div>
            </div>
        </div>
    @empty
        <div class="karsilasma font-weight-bold text-center">
            No games played yet!
        </div>
    @endforelse
@endsection
