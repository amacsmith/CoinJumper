@extends('layouts.app')

    @section('content')
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header" style="height: 125px;">

                        <span class="h4">Coin Pair Change Log</span>

                    </div>

                    <div class="card-block">

                        <div id="pair-change-log">

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header" style="height: 125px;">

                        <span class="h4">Ticker Data</span>

                        <div class="row mt-3">

                            <div class="col-lg-6">
                                <span class="h4" id="coin-pair"></span><span class="ml-3 h4" id="24h-volume"></span>
                            </div>
                        </div>

                    </div>

                    <div class="card-block">

                        <div id="ticker-data"></div>

                    </div>
                </div>
            </div>
        </div>
    @endsection

<script>
        //run "npm install socketcluster-client" in terminal before attempting to use

        // A $( document ).ready() block.
        $( document ).ready(function() {

            getExchanges();
            getPairs();

            timeout();

        });


        function timeout() {
            setTimeout(function () {
                // Do Something Here
                // Then recall the parent function to
                // create a recursive loop.

                console.log("looping...");
                checkExchangePairs("BINA");

                timeout();
            }, 1000);
        }

        let last_ticker;

        let first_ticker = true;

        function addTickerData(ticker_data) {

            $('#ticker-data').prepend('' +

                '<div class="form-group row mb-3 ">' +
                '<label class="col-sm-2 col-form-label">Last Trade</label>' +
                '<div class="col-sm-10">' +
                '<p class="form-control-static">' + ticker_data.last_price + '<i class="ml-3 indicator-change"></i></p>' +
                '</div>' +
                '</div>' +
                '<div class="form-group row">' +
                '<label class="col-sm-2 col-form-label">Current Volume</label>' +
                '<div class="col-sm-10">' +
                '<p class="form-control-static">' + ticker_data.volume_24 + '</p>' +
                '</div>' +
                '</div>' +
                '<div class="form-group row">' +
                '<label class="col-sm-2 col-form-label">Time</label>' +
                '<div class="col-sm-10">' +
                '<p class="form-control-static">' + Date.now() + '</p>' +
                '</div>' +
                '</div>' +
                '<hr class="mb-3">'
            );

            let indicator_change_el = $('.indicator-change');

            if(!first_ticker) {

                console.log('ticker_data.last_price : ' + ticker_data.last_price + ' > ' + last_ticker.last_price + ' : last_ticker');

                if (ticker_data.last_price > last_ticker.last_price) {
                    indicator_change_el.after('<i class="fa fa-chevron-up" style="color: lawngreen"></i>');
                } else {
                    indicator_change_el.after('<i class="fa fa-chevron-down" style="color: red"></i>');
                }
            } else {
                console.log('set ticker false');
                indicator_change_el.after('<i class="fa fa-btc fa-lg" style="color: blue"></i>');
                first_ticker = false;
            }
            indicator_change_el.removeClass('indicator-change');
        }

        let api_credentials =
            {
                "apiKey": "340b717f71cdc7bd8621f13c64805e00",
                "apiSecret": "157948613018978b66ca89278edcece9"
            };

        let options = {
            hostname: "sc-02.coinigy.com",
            port: "443",
            secure: "true"
        };

        console.log(options);
        let SCsocket = socketCluster.connect(options);


        function getPairs() {

            let options = JSON.stringify("BINA");

            axios.get("/getpairs/" + options)
                .then(function(data){

                    console.log(data);

            });

        }

        function checkExchangePairs(exchange_code){
            let options = JSON.stringify(exchange_code);

            axios.get("/check/exchange/pairs/" + options)
                .then(function(data){
                    console.log(data.data);

                    console.log("^^^^^^^^^ results of exchange pair updates ^^^^^^^^^^^");

                    if(data.data.length > 0){
                        $.each(data.data, function(key, value){
                            $('#pair-change-log').prepend(
                                '<div class="row">' +
                                    '<span>'+ value +'</span>' +
                                '</div>')
                        })
                    } else {

                        let currentdate = new Date();

                        $('#pair-change-log').prepend(
                            '<div class="row">' +
                                '<span>No change '+ (currentdate.getMonth()+1) + "/" +  currentdate.getDate() + "/" + currentdate.getFullYear() + " @ " + currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds() + '</span>' +
                            '</div>')
                    }
                });
        }

        function getExchanges() {

            axios.get("/getexchanges")
                .then(function(data){

                    console.log(data);

                });

        }

        SCsocket.on('connect', function (status) {

    //        console.log(status);

            SCsocket.on('error', function (err) {
                console.log(err);
            });


            SCsocket.emit("auth", api_credentials, function (err, token) {

                if (!err && token) {

                    let private_user_channel = SCsocket.subscribe('B03E6548-8A35-8485-D186-EDCF19454426');

                    private_user_channel.watch(function (data) {

                        if (data.MessageType === "Favorite") {
                            let count;
                            for (count = 0; count < data.Data.length; count++) {

                                let tic_data = data.Data[count];

                                addTickerData(tic_data);

                                last_ticker = tic_data;

                                $('#24h-volume').text(tic_data.volume_24 + ' Volume');
                                $('#coin-pair').text(tic_data.display_name);

    //                            'display_name': tic_data.display_name,
    //                            'mkt_name': tic_data.mkt_name,

                                let options = JSON.stringify({
                                    'base_curr': tic_data.base_curr,
                                    'exch_code': tic_data.exch_code,
                                    'exch_name': tic_data.exch_name,
                                    'primary_curr': tic_data.primary_curr,
                                    'volume_24': tic_data.volume_24,
                                    'mkt_id': tic_data.mkt_id,
                                    'exchmkt_id': tic_data.exchmkt_id,
                                    'exch_id': tic_data.exch_id,
                                    'btc_volume_24': tic_data.btc_volume_24,
                                    'last_price': tic_data.last_price
                                });

                                console.log(options);

                                axios.post("/ticker/" + options)
                                    .then(function(data){

                                        console.log(data);

                                    });

    //                            $.ajax({
    //                                url: '/api/twilio/sms',
    //                                data: {
    //                                    number: "3176006015",
    //                                    message: "Buy " + tic_data.display_name + " ASAP. The current price is " + tic_data.last_price
    //                                },
    //                                type: 'POST',
    //                                success: function(data){
    //
    //                                }
    //                            });

                            }
                        }

                        console.log(data);
                    });

    //                let market_binance_channel = SCsocket.subscribe("MARKETS-BINA");
    //
    //                console.log(market_binance_channel);
    //
    //                SCsocket.emit("MARKETS-BINA", null, function(err, data) {
    //
    //                    if(!err){
    //                        console.log(data);
    //                        console.log('^^^^^^^^ MARKET-BINA ^^^^^^^^^^ WEBSOCKET ^^^^^^^^^^^^^');
    //                    } else {
    //                        console.log(err);
    //                    }
    //
    //                });



                    console.log(SCsocket.subscriptions());
                    console.log('^^^^^subscriptions^^^^^');

                    SCsocket.emit("exchanges", null, function (err, data) {
                        if (!err) {
                            console.log(data);

                        } else {
                            console.log(err)
                        }
                    });

                    SCsocket.emit("channels", "OK", function (err, data) {

                        console.log("channels");

                        console.log(data);

                        if (!err) {
                            console.log(data);
                        } else {
                            console.log(err)
                        }
                    });
    //
    //
    //                SCsocket.emit("channels", "OK", function (err, data) {
    //                    if (!err) {
    //                        console.log(data);
    //                    } else {
    //                        console.log(err)
    //                    }
    //                });

                } else {
                    console.log(err)
                }
            });



        });

    </script>


