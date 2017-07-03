<?php
/**
 * Created by PhpStorm.
 * User: Bernadette
 * Date: 23/04/2017
 * Time: 1:32 PM
 */

//global confirm delete fn
if(! function_exists('confirmDlt')){
    function confirmDlt($id, $entity) {
        try{

            if($entity == 'rosters'){
                $msg = 'Consider this carefully because, for eg, if a shift for a particular date is being deleted,
                    this will delete all the shift details for that date.';

            }
            if($entity == 'employees'){
                $msg = 'Consider this carefully because, for eg, if an employee is being deleted,
                     all shifts assigned to the employee will also be deleted etc.';
            }


            return view('confirm-delete')->with(array('id' => $id, 'url' => $entity, 'msg' => $msg));

        } catch(\ErrorException $error){
            echo $error;
            Redirect::to('/rosters');
        }
        catch (GuzzleHttp\Exception\BadResponseException $e) {
            echo $e;
            Redirect::to('/rosters');
        }
    }
}
