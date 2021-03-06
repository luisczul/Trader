<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
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
         <td style="background-color:{{ Helper::color('Down') }}">{{ Session::get("volume.moving") }}</td>
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
         <td style="background-color:{{ Helper::color('Up') }}">1</td>
         <td style="background-color:{{ Helper::color('Down') }}">1</td>
         <td style="background-color:{{ Helper::color('Up') }}">1</td>
         <td style="background-color:{{ Helper::color('Up') }}">1</td>
        </tr>
        <tr>
            <td colspan="4">Tendency</td>
        </tr>
        <tr>
            <th>Up</th>
            <th>Down</th>
            <th>10 sec Tendency</th>
            <th>100 sec Tendency</th>
        </tr>       
        <tr>
         <td style="background-color:{{ Helper::color('Up') }}">{{ Session::get("tendency.up") }}</td>
         <td style="background-color:{{ Helper::color('Down') }}">{{ Session::get("tendency.down") }}</td>
         <td style="background-color:{{ Helper::color( Session::get("tendency.tendency10") ) }}">{{ Session::get("tendency.tendency10") }}</td>
         <td style="background-color:{{ Helper::color( Session::get("tendency.tendency100") ) }}"1>{{ Session::get("tendency.tendency100") }}</td>
        </tr>
            
    </table>
</div>  