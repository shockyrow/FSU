@extends('layouts.app')

@section('content')
  @forelse ($week_games as $game)
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
            <div class="col-md-2">
              -
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
      No games this week!
    </div>
  @endforelse
  @if (!empty($guesses))
    <div class="box" style="margin-bottom: 16px;">
      <canvas id="chart-area"></canvas>
    </div>
    <script>
        var randomScalingFactor = function() {
            return Math.round(Math.random() * 100);
        };

        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                      @forelse ($guesses as $guess)
                      {{ $guess["count"] }},
                      @empty
                      randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor()
                      @endforelse
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                  @forelse ($guesses as $guess)
                      '{{ $guess["name"] }}',
                  @empty
                      'Fenerbahçe',
                    'Galatasaray',
                    'Beşiktaş',
                    'Sivas Spor'
                  @endforelse
                ]
            },
            options: {
                responsive: true,
                legend: {
                    labels: {
                        usePointStyle: true
                    }
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById('chart-area').getContext('2d');
            window.myPie = new Chart(ctx, config);
        };

        /*document.getElementById('randomizeData').addEventListener('click', function() {
            config.data.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });
            });

            window.myPie.update();
        });*/

        var colorNames = Object.keys(window.chartColors);
        document.getElementById('addDataset').addEventListener('click', function() {
            var newDataset = {
                backgroundColor: [],
                data: [],
                label: 'New dataset ' + config.data.datasets.length,
            };

            for (var index = 0; index < config.data.labels.length; ++index) {
                newDataset.data.push(randomScalingFactor());

                var colorName = colorNames[index % colorNames.length];
                var newColor = window.chartColors[colorName];
                newDataset.backgroundColor.push(newColor);
            }

            config.data.datasets.push(newDataset);
            window.myPie.update();
        });

        document.getElementById('removeDataset').addEventListener('click', function() {
            config.data.datasets.splice(0, 1);
            window.myPie.update();
        });
    </script>
  @endif
  <div class="box" style="padding: 8px 16px;">
    <table class="table table-hover table-no-border">
      <thead>
      <tr>
        <th>#</th>
        <th>Club</th>
        <th>Played</th>
        <th>Win</th>
        <th>Drawn</th>
        <th>Lost</th>
        <th>GF</th>
        <th>GA</th>
        <th>GD</th>
        <th>Points</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($clubs_ranking as $club)
        <tr>
          <td class="font-weight-bold">{{ $loop->iteration }}</td>
          <td class="font-weight-bold">{{ $club['name'] }}</td>
          <td>{{ $club['played'] }}</td>
          <td>{{ $club['wins'] }}</td>
          <td>{{ $club['draws'] }}</td>
          <td>{{ $club['loses'] }}</td>
          <td>{{ $club['gf'] }}</td>
          <td>{{ $club['ga'] }}</td>
          <td>{{ $club['gd'] }}</td>
          <td class="font-weight-bold">{{ $club['points'] }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="10" class="text-center">No club found!</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>
@endsection
