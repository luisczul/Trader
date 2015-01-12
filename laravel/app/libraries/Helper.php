<?php

class Helper {

    public static function calculateTendency($low = 0,$high = 0,$price = 0,$low_p = 0,$high_p = 0,$price_p = 0) {
        
        if($price < $low and $price < $high & $low < $low_p) return -1;
        
        if($price > $low and $price > $high & $low > $low_p) return 1;

        if(abs($high - $price) > abs($price - $low) & $low < $low_p){
            return -1;
        } elseif(abs($high - $price) < abs($price - $low) & $low > $low_p){
            return 1;
        } else{
            return 0;
        }
    }

    public static function translateTendency($value) {
        if($value >= 1) return "Up";
        if(1 > $value && $value > -1) return "Flat";
        if($value <= -1) return "Down";
    }

    public static function chooseColor($max,$min,$color){
        if(is_numeric($color)){
            if($max > $min && $color == 1){

                if($color == "Up"){
                return "#00FF00 !important " ;
                }
                if($color == "Down"){
                    return "#FF0000 !important" ;
                }
                if($color == "Flat"){
                    return "#FFFFFF !important" ;
                }
         }

         if($max < $min && $color == -1){

                if($color == "Up"){
                return "#00FF00 !important " ;
                }
                if($color == "Down"){
                    return "#FF0000 !important" ;
                }
                if($color == "Flat"){
                    return "#FFFFFF !important" ;
                }
         }
        } else {
        if($max > $min && $color == "Up"){

                if($color == "Up"){
                return "#00FF00 !important " ;
                }
                if($color == "Down"){
                    return "#FF0000 !important" ;
                }
                if($color == "Flat"){
                    return "#FFFFFF !important" ;
                }
         }

         if($max < $min && $color == "Down"){

                if($color == "Up"){
                return "#00FF00 !important " ;
                }
                if($color == "Down"){
                    return "#FF0000 !important" ;
                }
                if($color == "Flat"){
                    return "#FFFFFF !important" ;
                }
         }
     }
        
    }

    public static function color($val){
    	

        if(is_numeric($val)){
            if($val > -1 && $val < 1 ){
                return "#FFFFFF !important" ;
            }
            if($val >= 1){
                return "#00FF00 !important" ;
            }
            if($val <= -1){
                
                return "#FF0000 !important" ;
            }
        
        } else {
            if($val == "Up"){
            return "#00FF00 !important " ;
            }
            if($val == "Down"){
                return "#FF0000 !important" ;
            }
            if($val == "Flat"){
                return "#FFFFFF !important" ;
            }
        }        
        
    }

    public static function colorDirection($val){
        

        if(is_numeric($val)){
            if($val == 0){
                return "#FFFFFF !important" ;
            }
            if($val > 0){
                return "#00FF00 !important" ;
            }
            if($val < 0){
                
                return "#FF0000 !important" ;
            }
        
        } else {
            if($val == "Up"){
            return "#00FF00 !important " ;
            }
            if($val == "Down"){
                return "#FF0000 !important" ;
            }
            if($val == "Flat"){
                return "#FFFFFF !important" ;
            }
        }        
        
    }

    public static function average($arr)
    {
    if (!is_array($arr)) return 0;
    return array_sum($arr)/count($arr);
    }
    
    public static function variance($aValues, $bSample = false){
        $fVariance = 0.0;
        if(count($aValues) > 0){
            $fMean = array_sum($aValues) / count($aValues);
            
            foreach ($aValues as $i)
            {
            $fVariance += pow($i - $fMean, 2);
            }
            $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
        }
    return $fVariance;
    }
    /**
    * Calculate standard deviation of array, by definition it is square root of variance
    * @param (array) $aValues
    * @return float
    */

    public static function standard_deviation($aValues, $bSample = false)
    {
    $fVariance = variance($aValues, $bSample);
    return (float) sqrt($fVariance);
    }
    /**
    * Calculate Skewness and Kurtosis, two parameters are sent by reference, so be carefull
    * @param (array) $array, (reference float) &amp;$skew, (reference float) &amp;$kurt
    */

    public static function skewnessandkurtosis($array, $skew, $kurt) {
    $skew = "N/A";
    $kurt = "N/A";
    $amount = count($array);
    if ($amount > 2) {
    for ($i = 0, $m2 =0,$m3=0,$m4=0; $i < $amount; $i++) {
    $array [$i] -= average($array);
    $m2 += pow($array[$i], 2);
    $m3 += pow($array[$i], 3);
    $m4 += pow($array[$i], 4);
    }
    $m2 /= $amount;
    $m3 /= $amount;
    $m4 /= $amount;
    $skew = $m3 / pow($m2, 1.5);
    $skew *= sqrt($amount*($amount-1))/ ($amount-2);
    if ($amount > 3) {
    $kurt = ($m4/ pow($m2, 2))-3;
    $kurt = (($amount+1)*$kurt)+6;
    $kurt *= ($amount-1)/(($amount-2)*($amount-3));
    }
    }
    }
 

   

}

?>