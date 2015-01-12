<?php

class TraderController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public $debug = true;


	public function index()
	{
		Session::flush();
		$this->initializeSession();
		$client = new \Scheb\YahooFinanceApi\ApiClient();

	
		$data = $client->getQuotes("DUST"); //Single stock
		//Fetch full data set
		if(!Session::has("stock") or Session::get("stock") == "") Session::put("stock","DUST");
		Session::save();
		
		
		$data = $client->getQuotes(Session::get("stock")); //Single stock

		
		$stockInfoSession = Session::get("stockInfo");
		$stockInfo = $data["query"]["results"]["quote"];
		$quote = file_get_contents('http://finance.google.com/finance/info?client=ig&q=DUST');
	  	//Remove CR's from ouput - make it one line
	  	$json = str_replace("\n", "", $quote);
	  
	  	//Remove //, [ and ] to build qualified string  
	  	$data = substr($json, 4, strlen($json) -5);
	    
	  	//decode JSON data
	  	$json_output = json_decode($data, true);
		$stockInfo["LastTradePriceOnly"] = $json_output["l"];
		
		return View::make('dashboard')
			->with("results",$stockInfo);
	}


	public function simulator($stock){
		$randomVolume = rand(1,10000);
		
		$previousPrice = $stock["LastTradePriceOnly"];
		$gap = 5;
		$randomPrice = rand($previousPrice - $gap, $previousPrice + $gap);
		$randomBid = rand($previousPrice - $gap, $previousPrice);
		$randomAsk = rand($previousPrice, $previousPrice + $gap);
		if(!isset($stock["Volume"])) $stock["Volume"] = 0;
		if(!isset($stock["AverageDailyVolume"])) $stock["AverageDailyVolume"] = 0;
		$stock["Volume"] += $randomVolume;
		$stock["BidRealtime"] = $randomBid;
		$stock["AskRealtime"] = $randomAsk;
		$stock["LastTradePriceOnly"] = $randomPrice;


		return $stock;
	}

	public function initializeSession(){
		Session::put("status",true);
		Session::put("stockInfoSession",array());
		Session::put("counter",1000);
		Session::put("stock","DUST");
		Session::put("previousPrice",0);

		Session::put("volume",array(
			"average" => 0,
			"momentum" => 0,
			"volume" => 0,
			"difference" => 0,
			"previous" => 0,
			"moving" => 0
		));

		Session::put("parameters",array(
			"tendencyPeriod" => 0,
			"volumePeriod" => 0,
			"lowPrice" => 0,
			"highPrice" => 0
		));

		Session::put("techincals",array(
			"shoulder1" => 0,
			"shoulder2" => 0,
			"head" => 0,
			"divergent" => 0,
			"flag" => 0
		));

		Session::put("tendency",array(
			"up" => 0,
			"down" => 0,
			"tendency10" => 0,
			"tendency10C" => 0,
			"tendency50" => 0,
			"tendency50C" => 0,
			"tendency100" => 0,
			"tendency100C" => 0,
			"tendency300" => 0,
			"tendency300C" => 0,
		));

		Session::put("direction",array(
			"direction" => 0,
			"tendency10" => 0,
			"tendency50" => 0,
			"tendency100" => 0,
			"tendency300" => 0,
		));

		Session::put("trade",array(
			"gainCash" => 0,
			"gainCashC" => 0,
			"gainPercentage" => 0,
			"gainPercentageC" => 0,
			"priceBought" => 0,
			"currentPrice" => 0,
			"sharesBought" => 0,
			"price" => 0,
			"paid" => 0,
			"total" => 0,
			"totalC" => 0
		));

		Session::save();

	}

	public function stockData(){

		if(!Session::has("stockInfoSession")) Session::put("stockInfoSession",array());
		if(!Session::has("counter")) Session::put("counter",1000);
		



		Session::save();

		$counter = Session::get("counter");


		$stockInfoSession = Session::get("stockInfoSession");

		$client = new \Scheb\YahooFinanceApi\ApiClient();
		$data = $client->getQuotes(Session::get("stock")); //Single stock
		$stockInfo = $data["query"]["results"]["quote"];
		
		$stockInfo["time"] = date("H:i:s");
		
		$turns = array("Up"=>0,"Down"=>0);


		if($this->debug){

			if(count($stockInfoSession) > 0){

				$stockInfo = $this->simulator(Session::get("previousStock"));
				
			} else {
				$stockInfo = $this->simulator($stockInfo);
				$stockInfo["iVolume"] = 0;

			}
			
		
			
		}



		
		if(Session::get("volume.previous") != 0){
			$stockInfo["iVolume"] = $stockInfo["Volume"]-Session::get("volume.previous");
		} else {
			$stockInfo["iVolume"] = 0;
		}

		$previousStock = Session::get("previousStock");

		$tendency = Helper::calculateTendency($stockInfo["BidRealtime"],$stockInfo["AskRealtime"],$stockInfo["LastTradePriceOnly"],$previousStock["BidRealtime"],$previousStock["AskRealtime"],$previousStock["LastTradePriceOnly"]);
		
		$stockInfo["tendency"] = $tendency;


		
			

		array_unshift($stockInfoSession,$stockInfo);

		if($counter < 0){
			array_pop($stockInfoSession);
		}
		$counter--;

		
		$turns = $this->calculateFullLoop($stockInfoSession);
		if($stockInfo["LastTradePriceOnly"] != $previousStock["LastTradePriceOnly"] || $stockInfo["BidRealtime"] != $previousStock["BidRealtime"] || $stockInfo["AskRealtime"] != $previousStock["AskRealtime"] || $stockInfo["iVolume"] != $previousStock["iVolume"]){
			$this->calculateMovingVolume();
			

			$averageMomentum = $stockInfo["iVolume"] - $turns["volumeMomentumAverage"];

			Session::put("trade.price",$stockInfo["LastTradePriceOnly"]);
			Session::put("volume.volume",$stockInfo["iVolume"]);
			Session::put("counter",$counter);
			Session::put("stockInfoSession",$stockInfoSession);
			Session::put("volume.moving",$averageMomentum);
			Session::put("volume.difference",$stockInfo["Volume"]-$stockInfo["AverageDailyVolume"]);
			
			
			Session::put("volume.previous",$stockInfo["Volume"]);

			//echo $this->calculateTendencyMoving(10,$stockInfoSession);
			$results = $this->calculateDirectionMoving(2,$stockInfoSession);
			Session::put("direction.direction",number_format($results["direction"],2));
			$results = $this->calculateTendencyMoving(10,$stockInfoSession);
			Session::put("tendency.tendency10",$results["tendency"]);
			Session::put("tendency.tendency10C",$results["colorT"]);
			$results = $this->calculateDirectionMoving(10,$stockInfoSession);
			Session::put("direction.tendency10",number_format($results["direction"],2));
			$results = $this->calculateTendencyMoving(50,$stockInfoSession);
			Session::put("tendency.tendency50",$results["tendency"]);
			Session::put("tendency.tendency50C",$results["colorT"]);
			$results = $this->calculateDirectionMoving(50,$stockInfoSession);
			Session::put("direction.tendency50",number_format($results["direction"],2));
			$results = $this->calculateTendencyMoving(100,$stockInfoSession);
			Session::put("tendency.tendency100",$results["tendency"]);
			Session::put("tendency.tendency100C",$results["colorT"]);
			$results = $this->calculateDirectionMoving(100,$stockInfoSession);
			Session::put("direction.tendency100",number_format($results["direction"],2));
			$results = $this->calculateTendencyMoving(300,$stockInfoSession);
			Session::put("tendency.tendency300",$results["tendency"]);
			Session::put("tendency.tendency300C",$results["colorT"]);
			$results = $this->calculateDirectionMoving(300,$stockInfoSession);
			Session::put("direction.tendency300",number_format($results["direction"],2));
			$this->calculateGain();


			if($tendency >= 1) Session::put("tendency.up",Session::get("tendency.up")+1);
			if($tendency <= -1) Session::put("tendency.down",Session::get("tendency.down")+1);
			//if($tendency == 0) Session::put("tendency.flat",Session::get("tendency.flat")+1);
		}

		Session::put("previousStock",$stockInfo);

		Session::save();

		


		return View::make("widgets.stockData")
			->with("entries",$stockInfoSession)
			->with("turnUp",$turns["Up"])
			->with("turnDown",$turns["Down"]);
	}

	public function calculateGain(){
		
			$comission = 9.95;
			$priceBought = Session::get("trade.priceBought");
			$price = Session::get("trade.price");
		if($priceBought != 0){
			$percentage = (($price/$priceBought) - 1)*50;
			
			$paid = ( $priceBought*Session::get("trade.sharesBought")) + $comission;
			$total = $price*Session::get("trade.sharesBought");
			$cash = $total - $paid;

			$comission = 9.95;
			$priceBought = Session::get("trade.priceBought");
			$priceC = Session::get("trade.currentPrice");
		
			$percentageC = (($priceC/$priceBought) - 1)*50;
			
			$paidC = ( $priceBought*Session::get("trade.sharesBought")) + $comission;
			$totalC = $priceC*Session::get("trade.sharesBought");
			$cashC = $totalC - $paidC;


			Session::put("trade.paid",$paid);
			Session::put("trade.total",$total);
			Session::put("trade.gainCash",$cash);
			Session::put("trade.gainPercentage",$percentage);

			Session::put("trade.paidC",$paidC);
			Session::put("trade.totalC",$totalC);
			Session::put("trade.gainCashC",$cashC);
			Session::put("trade.gainPercentageC",$percentageC);

			Session::save();
		}
	}

	public function calculateTendencyMoving($length,$stocks){
		$up = 0;
		$down = 0;

		$upDirection = 0;
		$downDirection = 0;
		$direction = 0;

		$previousPrice = 0;

		$lastAsk = 0;
		$lastBid = 0;
		
		if(count($stocks) > $length){

		for($x = 0; $x < $length; $x++){

			$stock = $stocks[$x];

			if($x != 0){
				if($previousPrice < $stock["LastTradePriceOnly"]) $upDirection++;
				if($previousPrice > $stock["LastTradePriceOnly"]) $downDirection++;
			}

			$previousPrice = $stock["LastTradePriceOnly"];
			
			
			if($stock["tendency"] >= 1){
				$up++;
			} elseif($stock["tendency"] <= -1) {
				$down++;
			}
		}

		if($upDirection > $downDirection){
			$direction = "Up";
		} elseif($upDirection > $downDirection) {
			$direction = "Down";
		} else {
			$direction = "Flat";
		}


		if($up > $down) { 

			return array("colorT"=>$up,"tendency"=>$up."/-".$down,"direction"=>$direction);
		}
		return  array("colorT"=>"-".$down,"tendency"=>"-".$up."/-".$down,"direction"=>$direction);

		}
		return  array("colorT"=>0, "tendency"=>"","direction"=>$direction);

	}

	public function calculateDirectionMoving($length,$stocks){
		$first = 0;
		$last = 0;
		$first = $stocks[0]["LastTradePriceOnly"];
		$average = 0;

		if(count($stocks) >= $length){
			$average = 0;
			for($x = 0; $x < $length; $x++){
				$average += $stocks[$x]["LastTradePriceOnly"];
			}
			$average = $average/$length;
		}

		$last = $average;

		if($first > $last and $last != 0){
			$dif = $first-$last;
			$direction = $dif;
		} elseif($last > $first  and $last != 0) {
			$direction = $first-$last;
		} else {
			$direction = 0;
		}
		return  array("direction"=>$direction);
	}

	public function modify(){
		
		$symbol = Input::get("symbol");
		$priceBought = Input::get("priceBought");
		$sharesBought = Input::get("sharesBought");
		$currentPrice = Input::get("currentPrice");
		if(Session::get("stock") != $symbol) $this->initializeSession();
		Session::put("stock",$symbol);
		Session::put("trade.priceBought",$priceBought);
		Session::put("trade.sharesBought",$sharesBought	);
		Session::put("trade.currentPrice",$currentPrice	);
		Session::save();
	}

	public function calculateFullLoop($stocks){
		$up = 0;
		$down = 0;
		$previous = 0;
		$current = "";
		
		$averageBulk = 0;
		$average = 0;
		$averageCounter = 0;
	

		for($x = 0; $x < count($stocks); $x++){


			$stock = $stocks[$x];

			if($stock["iVolume"] != 0) { 
				$averageBulk += $stock["iVolume"]; 
				$averageCounter = $averageCounter+1; 
			}
			
			
			if(($stock["tendency"] >= 1 and $previous <= -1) or ($stock["BidRealtime"] > $stock["LastTradePriceOnly"])){
				$down++;
			}
			if(($stock["tendency"] <= -1 and $previous >= 1)or ($stock["BidRealtime"] > $stock["LastTradePriceOnly"])){
				$up++;
			}

			$previous = $stock["tendency"] ;

		}
		
		if($averageCounter != 0)
		$average = $averageBulk/$averageCounter;
	


		return array("Up"=>$up,"Down"=>$down,"volumeMomentumAverage"=>$average);
	}


	public function calculateMovingVolume(){
		
	}

	public function stockStatus(){
		if(!Session::has("stockInfoSession")) Session::put("stockInfoSession",array());
		if(!Session::has("counter")) Session::put("counter",50);
		if(!Session::has("stock")) Session::put("stock","DUST");
		Session::save();

		$counter = Session::get("counter");



		

		return View::make("widgets.stockStatus");
	}

}
