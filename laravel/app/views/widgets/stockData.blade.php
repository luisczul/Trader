<?php
$bidArray = array();
$askArray = array();
$priceArray = array();
$volume = array();
?>
<div class="col-lg-6">
                                  <div class="table-responsive">
                                              <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                  <th>#</th>
                                                  <th>Price</th>
                                                  <th>Bid</th>
                                                  <th>Ask</th>
                                                  <th>Spread</th>
                                                  <th>Tendency</th>
                                                </thead>
                                                    <tbody>
                                                    <?php $counter = 1; ?>
                                                    <?php $previousPrice = 0; ?>
                                                    <?php $previousPrice = Session::get("previousPrice"); ?>
                                                     @foreach ($entries as $data)
                                                     
                                                      <tr>
                                                        <th>{{ $counter }} <span class="text-muted">{{ (isset($data["time"])) ? $data["time"] : "" }}</span></th>
                                                        <td style="background:<?php   
                                                                    if($previousPrice < $data["LastTradePriceOnly"]) echo Helper::color("Up");  
                                                                    if($previousPrice > $data["LastTradePriceOnly"]) echo Helper::color("Down");  
                                                                    if($previousPrice == $data["LastTradePriceOnly"]) echo Helper::color("Flat");  
                                                          ?>">{{ $data["LastTradePriceOnly"] }}</td>
                                                        <td>{{ $data["BidRealtime"] }}</td>
                                                        <td>{{ $data["AskRealtime"] }}</td>
                                                        <td>{{ abs($data["AskRealtime"] - $data["BidRealtime"] )  }}</td>
                                                        <td style="background-color:{{ Helper::color($data["tendency"]) }}">{{ Helper::translateTendency($data["tendency"]) }}</td>
                                                      </tr>
                                                      
                                                      <?php
                                                        if($counter == 1) { Session::put("previousPrice",$data["LastTradePriceOnly"]); Session::save(); } 
                                                        $previousPrice = $data["LastTradePriceOnly"];
                                                      ?>
                                                      <?php $counter++; ?>
                                                      <?php array_unshift($bidArray,$data["BidRealtime"]); ?>
                                                      <?php array_unshift($askArray,$data["AskRealtime"]); ?>
                                                      <?php array_unshift($priceArray,$data["LastTradePriceOnly"]); ?>
                                                      <?php array_unshift($volume,$data["iVolume"]); ?>
                                                     @endforeach
                                                    </tbody>
                                              </table>
                                          </div>  
                                                                          </div>
                                <div class="col-lg-4">
                                    <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fa fa-bar-chart-o fa-fw"></i> Parameters
                                    </div>
                                    <div class="table-responsive">
                                  <table class="table table-bordered table-hover table-striped">
                                  <tr>
                                          <td colspan="4">Direction</td>
                                      </tr>
                                      <tr>
                                          <th>Direction</th>
                                          <th>Direction 50</th>
                                          <th>Direction 100</th>
                                          <th>Direction 300</th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:{{ Helper::colorDirection(Session::get("direction.direction")) }}">{{ Session::get("direction.direction") }}</td>
                                       <td style="background-color:{{ Helper::colorDirection( Session::get("direction.tendency50") ) }}"1>{{ Session::get("direction.tendency50") }}</td>
                                       <td style="background-color:{{ Helper::colorDirection( Session::get("direction.tendency100") ) }}">{{ Session::get("direction.tendency100") }}</td>
                                       <td style="background-color:{{ Helper::colorDirection( Session::get("direction.tendency300") ) }}"1>{{ Session::get("direction.tendency300") }}</td>
                                      </tr>
                                      
                                      <tr>
                                          <td colspan="4">Tendency</td>
                                      </tr>
                                      <tr>
                                          <th>Up</th>
                                          <th>Down</th>
                                          <th>Turns Up</th>
                                          <th>Turns Down</th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:{{ Helper::chooseColor(Session::get("tendency.up"),Session::get("tendency.down"),'Up') }}">{{ Session::get("tendency.up") }}</td>
                                       <td style="background-color:{{ Helper::chooseColor(Session::get("tendency.up"),Session::get("tendency.down"),'Down') }}">{{ Session::get("tendency.down") }}</td>
                                       <td style="background-color:{{ Helper::chooseColor($turnUp,$turnDown,"Up")  }}">{{ $turnUp }}</td>
                                       <td style="background-color:{{ Helper::chooseColor($turnUp,$turnDown,"Down")  }}">{{ $turnDown }}</td>
                                      </tr>
                                      <tr>
                                          <th>Tendency 10</th>
                                          <th>Tendency 50</th>
                                          <th>Tendency 100</th>
                                          <th>Tendency 300</th>
                                          <th></th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:{{ Helper::color( Session::get("tendency.tendency10C") ) }}">{{ Session::get("tendency.tendency10") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("tendency.tendency50C") ) }}"1>{{ Session::get("tendency.tendency50") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("tendency.tendency100C") ) }}">{{ Session::get("tendency.tendency100") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("tendency.tendency300C") ) }}"1>{{ Session::get("tendency.tendency300") }}</td>
                                      </tr>
                                      <tr>
                                          <td colspan="4">Volume</td>
                                      </tr>
                                    <tr>
                                          <th>Volume Average</th>
                                      <th>Volume Momentum</th>
                                      <th>Volume Diff Average</th>
                                      <th>Immediate Volume</th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:{{ Helper::color('Up') }}">{{ Session::get("volume.average") }}</td>
                                       <td style="background-color:{{ Helper::color(Session::get("volume.moving")) }}">{{ Session::get("volume.moving") }}</td>
                                       <td style="background-color:{{ Helper::color('Up') }}">{{ Session::get("volume.difference") }}</td>
                                       <td style="background-color:{{ Helper::color('Up') }}">{{ Session::get("volume.volume") }}</td>
                                      </tr>
                                      <tr>
                                          <td colspan="4">Technicals</td>
                                      </tr>
                                      <tr>
                                          <th>Shoulders/Head</th>
                                          <th>Shoulders</th>
                                          <th>Divergent</th>
                                          <th>Flag</th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:"></td>
                                       <td style="background-color:"></td>
                                       <td style="background-color:"></td>
                                       <td style="background-color:"></td>
                                      </tr>
                                      
                                      <!-- <tr>
                                          <td colspan="4">Moving Averages</td>
                                      </tr>
                                      <tr>
                                          <th>SMA 10</th>
                                          <th>SMA 50 </th>
                                          <th>EMA 10</th>
                                          <th>EMA 50 </th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:{{ Helper::color(Session::get("direction.direction"),Session::get("tendency.down"),'Up') }}">{{ Session::get("direction.direction") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("direction.tendency50") ) }}"1>{{ Session::get("direction.tendency50") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("direction.tendency100") ) }}">{{ Session::get("direction.tendency100") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("direction.tendency300") ) }}"1>{{ Session::get("direction.tendency300") }}</td>
                                      </tr>
                                      <tr>
                                          <th>Differences 10</th>
                                          <th>SMA 50 </th>
                                          <th>EMA 10</th>
                                          <th>EMA 50 </th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:{{ Helper::color(Session::get("direction.direction"),Session::get("tendency.down"),'Up') }}">{{ Session::get("direction.direction") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("direction.tendency50") ) }}"1>{{ Session::get("direction.tendency50") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("direction.tendency100") ) }}">{{ Session::get("direction.tendency100") }}</td>
                                       <td style="background-color:{{ Helper::color( Session::get("direction.tendency300") ) }}"1>{{ Session::get("direction.tendency300") }}</td>
                                      </tr> -->
                                          
                                  </table>
                              </div>  
                                    </div>

                                    <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fa fa-bar-chart-o fa-fw"></i> GAIN
                                    </div>
                                    <div class="table-responsive">
                                  <table class="table table-bordered table-hover table-striped">
                                      <tr>
                                          <td colspan="4">Volume</td>
                                      </tr>
                                    <tr>

                                      <th>Gain $</th>
                                      <th>Gain %</th>
                                      <th>Price Bought</th>
                                      <th>Price</th>
                                      <th>Paid</th>
                                      <th>Total</th>
                                      </tr>       
                                      <tr>
                                       <td style="background-color:{{ Helper::colorDirection(Session::get("trade.gainCash")) }}">{{ number_format(Session::get("trade.gainCash"),2) }}</td>
                                       <td style="background-color:{{ Helper::colorDirection(Session::get("trade.gainPercentage")) }}">{{ number_format(Session::get("trade.gainPercentage"),2) }}</td>
                                       <td>
                                       {{ number_format(Session::get("trade.priceBought"),2) }}</td>
                                       <td>{{ Session::get("trade.price") }}</td>
                                       <td>{{ number_format(Session::get("trade.paid"),2) }}</td>
                                       <td>{{ number_format(Session::get("trade.total"),2) }}</td>
                                      </tr>
                                      <tr>
                                       <td style="background-color:{{ Helper::colorDirection(Session::get("trade.gainCashC")) }}">{{ number_format(Session::get("trade.gainCashC"),2) }}</td>
                                       <td style="background-color:{{ Helper::colorDirection(Session::get("trade.gainPercentageC")) }}">{{ number_format(Session::get("trade.gainPercentageC"),2) }}</td>
                                       <td>
                                       {{ number_format(Session::get("trade.priceBought"),2) }}</td>
                                       <td>{{ Session::get("trade.currentPrice") }}</td>
                                       <td>{{ number_format(Session::get("trade.paidC"),2) }}</td>
                                       <td>{{ number_format(Session::get("trade.totalC"),2) }}</td>
                                      </tr>
         
                                      
                                      
                                          
                                  </table>
                              </div>  
                                    </div>
                                </div>

<?php $labels = range(0,count($bidArray)); ?>
<?php $labels = array_fill(0,count($labels),""); ?>
{{ HTML::script('fw/chartjs/Chart.js'); }}
<script>
var newCanvas = $('<canvas/>',{
                   'class':'radHuh',
                    id: 'myChart'                   
                }).prop({
                    width: 700,
                    height: 400
                });
$('#graphContainer').html(newCanvas);
  // Get context with jQuery - using jQuery's .get() method.
var ctx = newCanvas.get(0).getContext("2d");

// This will get the first returned node in the jQuery collection.
var options = {
    animation : false,  // Edit: correction typo: from 'animated' to 'animation'
    pointDotRadius : 2,
}

var data = {
    labels: {{ json_encode($labels) }},
    datasets: [
        {
            label: "BID",
            fillColor: "rgba(220,220,220,0)",
            strokeColor: "rgba(255,0,0,0.5)",
            pointColor: "rgba(220,220,220,0.5)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,0.5)",
            data: {{ json_encode($bidArray) }}
        },
        {
            label: "ASK",
            fillColor: "rgba(151,187,205,0)",
            strokeColor: "rgba(0,255,0,0.5)",
            pointColor: "rgba(151,187,205,0.5)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,0.5)",
            data: {{ json_encode($askArray) }}
        },
        {
            label: "PRICE",
            fillColor: "rgba(151,187,205,0)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(0,0,0,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: {{ json_encode($priceArray) }}
        }
    ]
};
var myLineChart = new Chart(ctx).Line(data, options);

                                
<?php $labels = range(0,count($bidArray)); ?>
<?php $labels = array_fill(0,count($labels),""); ?>


var newCanvas = $('<canvas/>',{
                   'class':'radHuh',
                    id: 'myChart2'                   
                }).prop({
                    width: 700,
                    height: 200
                });
$('#graphContainer2').html(newCanvas);
  // Get context with jQuery - using jQuery's .get() method.
var ctx = newCanvas.get(0).getContext("2d");

// This will get the first returned node in the jQuery collection.
var options = {
    animation : false,  // Edit: correction typo: from 'animated' to 'animation'
    pointDotRadius : 2,
}

var data = {
    labels: {{ json_encode($labels) }},
    datasets: [
        {
            label: "Volume",
            fillColor: "rgba(220,220,220,0)",
            strokeColor: "rgba(255,0,0,0.5)",
            pointColor: "rgba(220,220,220,0.5)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,0.5)",
            data: {{ json_encode($volume) }}
        }
    ]
};
var myLineChart = new Chart(ctx).Line(data, options);

</script>
