@extends('layout.layout')


@section("scriptsHead")
<script>
$(document).ready(function(){
     //setInterval(callStockData(),000);
                 window.setInterval( callStockData   ,3000 ); // 5 seconds

       
});
</script>
@endsection

@section('content')



        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Trader</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <!-- /.row -->
            
		<div class="row">
                <div class="col-lg-12">
                    
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Parameters
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                <div class="col-lg-6">
                    
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                           <iframe
 src="https://www.goldbroker.com/widget/iframe/live/XAU/320?currency=USD" 
width="100%" height="320" style="border: 0; overflow: 
hidden;"></iframe><br>Gold price by <a 
href="https://www.goldbroker.com">GoldBroker.com</a>
<!-- Begin Gold Price Script - GOLDPRICE.ORG -->


                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                </div>
                <div class="col-lg-6">
                    <div id="graphContainer">
                        <canvas id="myChart" width="400" height="400"></canvas>
                    </div>
                    <div id="graphContainer2">
                        <canvas id="myChart2" width="400" height="200"></canvas>
                    </div>
                </div>
                <!-- /.col-lg-8 -->
                
                <!-- /.col-lg-4 -->
            </div>
                            <div class="row">
                                <div id="stockData"></div>
                               
                                <div class="col-lg-2">
                                <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-bar-chart-o fa-fw"></i> Parameters
                                    </div>
                                    <div class="panel-body">
                       
                                    <form action="/Trade/modify" id="modifyForm" method="POST">
                                        <div class="form-group">
                                            <label>SYMBOL</label>
                                            <input class="form-control" id="symbol" name="symbol" placeholder="Symbol" onBlur="triggerModify()">
                                          <!--   <p class="help-block">Example block-level help text here.</p> -->
                                        </div>
                                        <div class="form-group">
                                            <label>Price Bought</label>
                                            <input class="form-control" id="priceBought" name="priceBought" placeholder="Price Period"  onkeyup="triggerModify()">
                                          <!--   <p class="help-block">Example block-level help text here.</p> -->
                                        </div>
                                        <div class="form-group">
                                            <label>Shares Bought</label>
                                            <input class="form-control" id="sharesBought" name="sharesBought" placeholder="Shares Period"  onkeyup="triggerModify()">
                                          <!--   <p class="help-block">Example block-level help text here.</p> -->
                                        </div>
                                        <div class="form-group">
                                            <label>Current Price</label>
                                            <input class="form-control" id="currentPrice" name="currentPrice" placeholder="Current Price"  onkeyup="triggerModify()">
                                          <!--   <p class="help-block">Example block-level help text here.</p> -->
                                        </div>
                                        <div class="form-group">
                                            <label>Volume Period</label>
                                            <input class="form-control" id="volumePeriod" name="volumePeriod" placeholder="Volume Period">
                                        </div>
                                        <div class="form-group">
                                            <label>Low price accepted</label>
                                            <input class="form-control" id="tendencyPeriod" placeholder="Low price accepted">
                                        </div>
                                        <div class="form-group">
                                            <label>High price accepted</label>
                                            <input class="form-control" id="tendencyPeriod" placeholder="High price accepted">
                                        </div>
                                        <input name="submit" style="display:none" type="submit" class="btn btn-default" value="Submit"/>
                                        <input style="display:none" type="reset" class="btn btn-default"/>
                                    </form>
                                </div>
                                </div>
                                </div>
                                <!-- /.col-lg-4 (nested) -->
                                <div class="col-lg-8">
                                    <div id="morris-bar-chart"></div>
                                </div>
                                <!-- /.col-lg-8 (nested) -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
                
                <!-- /.col-lg-4 -->
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Bar Chart Example
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">

                                            <tbody>
                                               @foreach ($results as $data => $value)
                                               	<tr>
                                               		<th>{{ $data }}</th>
                                               		<td>{{ $value }}</td>
                                               	</tr>
                                               @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.col-lg-4 (nested) -->
                                <div class="col-lg-8">
                                    <div id="morris-bar-chart"></div>
                                </div>
                                <!-- /.col-lg-8 (nested) -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
                
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>


    @section("scripts")

    <script>
    var triggered = true;
    var triggered2 = true;



        



      function callStockData(){
      $.ajax(
	    {
	        url :"/widgets/stockData",
	        type: "POST",
	        data: {  },
	        success:function(data, textStatus, jqXHR) 
	        {
	        	$("#stockData").html(data);	
                //callStockStatus();
                
	        
	        },
	        error: function(jqXHR, textStatus, errorThrown) 
	        {
	        	errorMessage(jqXHR.responseText);
	        },
	        statusCode: {
		        500: function() {
		        	if(jqXHR.responseText != ""){
		        		errorMessage(jqXHR.responseText);
		        	}else {
		        		
		        	}
		            
		        }
		    }
	    });
      }

      function callStockStatus(){
      $.ajax(
        {
            url :"/widgets/stockStatus",
            type: "POST",
            data: {  },
            success:function(data, textStatus, jqXHR) 
            {
                $("#stockStatus").html(data); 
                
            
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                errorMessage(jqXHR.responseText);
            },
            statusCode: {
                500: function() {
                    if(jqXHR.responseText != ""){
                        errorMessage(jqXHR.responseText);
                    }else {
                        
                    }
                    
                }
            }
        });
      }

      function triggerModify(){
     
                $.ajax(
                {
                    url : "Trade/modify",
                    type: "POST",
                    data:  $('#modifyForm').serialize(),
                    success:function(data, textStatus, jqXHR) 
                    {
                        
                        return false;

                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        errorMessage(jqXHR.responseText);

                        return false;
                    },
                    statusCode: {
                        500: function() {
                            if(jqXHR.responseText != ""){
                                errorMessage(jqXHR.responseText);
                                hideLoadWithElement(preLoad);
                            }else {
                                hideLoadWithElement(preLoad);
                            }
                            
                        }
                    }
                });
             
      }
      //ALL AJAX FORMS SAVE

    </script>

    @endsection



@endsection