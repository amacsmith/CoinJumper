@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <span class="h3">Coin Pairs</span>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-4">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($coin_pairs as $coin_pair)
                                    @if($loop->first)
                                        <a class="list-group-item list-group-item-action active" id="list-{{$coin_pair->id}}-list" data-toggle="list" href="#list-{{$coin_pair->id}}" role="tab" aria-controls="{{$coin_pair->id}}">{{$coin_pair->mkt_name}}</a>
                                    @else
                                        <a class="list-group-item list-group-item-action" id="list-{{$coin_pair->id}}-list" data-toggle="list" href="#list-{{$coin_pair->id}}" role="tab" aria-controls="{{$coin_pair->id}}">{{$coin_pair->mkt_name}}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($coin_pairs as $coin_pair)
                                    @if($loop->first)
                                        <div class="tab-pane fade show active" id="list-{{$coin_pair->id}}" role="tabpanel" aria-labelledby="list-{{$coin_pair->id}}-list">{{$coin_pair}}</div>
                                    @else
                                        <div class="tab-pane fade" id="list-{{$coin_pair->id}}" role="tabpanel" aria-labelledby="list-{{$coin_pair->id}}-list">{{$coin_pair}}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



<script>

</script>

