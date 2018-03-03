@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-header">
                    <span class="h3">Exchanges</span>
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="list-group" id="list-tab" role="tablist">
                                @foreach($exchanges as $exchange)
                                    @if($loop->first)
                                        <a class="list-group-item list-group-item-action active" id="list-{{$exchange->id}}-list" data-toggle="list" href="#list-{{$exchange->id}}" role="tab" aria-controls="{{$exchange->id}}">{{$exchange->exch_name}}</a>
                                    @else
                                        <a class="list-group-item list-group-item-action" id="list-{{$exchange->id}}-list" data-toggle="list" href="#list-{{$exchange->id}}" role="tab" aria-controls="{{$exchange->id}}">{{$exchange->exch_name}}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach($exchanges as $exchange)
                                    @if($loop->first)
                                        <div class="tab-pane fade show active" id="list-{{$exchange->id}}" role="tabpanel" aria-labelledby="list-{{$exchange->id}}-list">
                                            @php
                                                $count = count((array)$exchanges_object);
                                            @endphp

                                            <span class="ml-5">{</span>

                                            @foreach($exchanges_object as $key => $value)

                                                <div class="row">

                                                    <div class="col-lg-3 offset-1">
                                                        <p class="text-justify font-weight-bold">"{{$key}}": </p>
                                                    </div>

                                                    @if($loop->iteration === $count)

                                                        <div class="col-lg-8">
                                                            <p class="text-justify">"{{$value}}"</p>
                                                        </div>

                                                    @else

                                                        <div class="col-lg-8">
                                                            <p class="text-justify">"{{$value}}",</p>
                                                        </div>

                                                    @endif

                                                </div>

                                            @endforeach

                                            <span class="ml-5">}</span>

                                        </div>
                                    @else
                                        <div class="tab-pane fade" id="list-{{$exchange->id}}" role="tabpanel" aria-labelledby="list-{{$exchange->id}}-list">
                                            <span class="ml-5">"{</span>
                                            @foreach($exchanges_object as $key => $value)
                                                <div class="row">
                                                    <div class="col-lg-3 offset-1">
                                                        <p class="text-justify font-weight-bold">"{{$key}}": </p>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <p class="text-justify">"{{$value}}"</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <span class="ml-5">}"</span>
                                        </div>
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

