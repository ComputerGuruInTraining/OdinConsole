@extends('layouts.master_layout')
@extends('sidebar')

@section('custom-menu-title')
    Shifts
@stop

@section('title-item')
    Shifts
@stop

@section('page-content')
    <div class="col-md-12">

        <div style="padding:15px 0px 10px 0px;">
            <button type="button" class="btn btn-success" onclick="window.location.href='rosters/create'">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Shift
            </button>
        </div>
        <div class='table-responsive'>
            <table class="table table-hover">
                <tr class="heading-row">
                    <th>Start Date</th>
                    <th>Time</th>
                    <th>Locations</th>
                    <th>Location Checks</th>
                    <th>Assigned to</th>
                    <th>Manage</th>
                </tr>

                @php
                    foreach($assigned as $index => $formattedShift){
                        //display the first record with values in all the columns
                       echo "<tr><td class='group-data' colspan='3'>".$formattedShift[0]->shift_title."</td></tr>
                             <tbody class='group-list'>
                                <tr><td>".$formattedShift[0]->unique_date."</td>
                                <td class='col-min-width'>".$formattedShift[0]->start_time." - ".$formattedShift[0]->end_time."</td>
                                <td class='col-min-width-med'>".$formattedShift[0]->unique_locations."</td>
                                <td>".$formattedShift[0]->checks."</td>
                                <td>".$formattedShift[0]->unique_employees."</td>
                                <td class='col-min-width-sm'>
                                    <a href='/rosters/".$formattedShift[0]->assigned_shift_id."/edit'>Edit</a>
                                    | <a href='/confirm-delete/".$formattedShift[0]->assigned_shift_id."/".$url."' style='color: #990000;'>Delete</a>
                                </td>
                         </tr>";

                        //variables needed for displaying multiple employees and locations
                        $i = 0;
                        $j = 0;
                        $locations = [];
                        $employees = [];
                        $checks = [];

                          foreach($assigned->get($index) as $shift){
                            if($formattedShift[0]->unique_locations != $shift->unique_locations){
                                if($shift->unique_locations != null){
                                     $locations[$i] = $shift->unique_locations;
                                     $checks[$i] = $shift->checks;
                                     $i++;

                                }
                            }
                            if($formattedShift[0]->unique_employees != $shift->unique_employees){
                                if($shift->unique_employees != null){
                                     $employees[$j] = $shift->unique_employees;
                                     $j++;

                                }
                            }
                          }

                         //integer values
                         $locLen = sizeof($locations);
                         $empLen = sizeof($employees);

                         $smallestArray = min($locLen, $empLen);
                         $biggestArray = max($locLen, $empLen);

                        //loop through the smallest array and display values from both the arrays
                        for($index = 0; $index<$smallestArray; $index++){
                         echo"<tr>
                            <td></td>
                            <td></td>
                            <td>".$locations[$index]."</td>
                            <td>".$checks[$index]."</td>
                            <td>".$employees[$index]."</td>
                            <td></td>
                            </tr>";

                        }
                        //check to see if the arrays are different sizes,
                        //and then check to see which array is the biggest array
                        //and loop through the biggest array which still has values to display
                        if(sizeof($locations) != sizeof($employees)){
                           if($biggestArray == sizeof($employees)){
                                for($r = $smallestArray; $r<$biggestArray; $r++){
                                   echo"<tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>".$employees[$r]."</td>
                                        <td></td>
                                        </tr>";
                               }
                           }
                           else if($biggestArray == sizeof($locations)){
                                for($r = $smallestArray; $r<$biggestArray; $r++){
                                       echo"<tr>
                                            <td></td>
                                            <td></td>
                                            <td>".$locations[$r]."</td>
                                            <td>".$checks[$r]."</td>
                                            <td></td>
                                            <td></td>
                                            </tr>";
                                   }
                           }
                       }
                        echo "</tbody>";
                     }
                @endphp

            </table>
        </div>
    </div>
@stop






