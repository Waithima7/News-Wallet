<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 5/12/18
 * Time: 10:16 AM
 */

namespace App\Repositories;


class StatusRepository
{
    public static function getProductStatus($state){
        $statuses = [
            'inactive'=>0,
            'active'=>1,
            'pending'=>0,
            'won'=>2,
            'canceled'=>3,
            'canceled_winning'=>4,
        ];
        if(is_array($state)){
            $states  = [];
            foreach($state as $st){
                $states[] = $statuses[$st];
            }
            return $states;
        }elseif(is_numeric($state)){
            $statuses = array_flip($statuses);
            return $statuses[$state];
        }
        return $statuses[$state];
    }
    public static function getWonStatus($state){
        $statuses = [
            'unpaid'=>0,
            'paid'=>1,
            'shipping'=>2,
            'shipped'=>3,
            'canceled'=>5,
            'sold'=>4
        ];
        if(is_array($state)){
            $states  = [];
            foreach($state as $st){
                $states[] = $statuses[$st];
            }
            return $states;
        }elseif(is_numeric($state)){
            $statuses = array_flip($statuses);
            return $statuses[$state];
        }
        return $statuses[$state];
    }
    public static function getPurchaseStatus($state){
        $statuses = [
            'unpaid'=>0,
            'paid'=>1,
            'shipping'=>2,
            'shipped'=>3,
            'canceled'=>5,
        ];
        if(is_array($state)){
            $states  = [];
            foreach($state as $st){
                $states[] = $statuses[$st];
            }
            return $states;
        }elseif(is_numeric($state)){
            $statuses = array_flip($statuses);
            return $statuses[$state];
        }
        return $statuses[$state];
    }
    public static function getWonCartStatus($state){
        $statuses = [
            'unpaid'=>0,
            'pending'=>0,
            'paid'=>1,
            'shipping'=>2,
            'shipped'=>3,
            'canceled'=>5,
            'sold'=>4,
        ];
        if(is_array($state)){
            $states  = [];
            foreach($state as $st){
                $states[] = $statuses[$st];
            }
            return $states;
        }elseif(is_numeric($state)){
            $statuses = array_flip($statuses);
            return $statuses[$state];
        }
        return $statuses[$state];
    }

    public static function getOrderStatus($state){
        $statuses = [
            'bidding'=>1,
            'inactive'=>0,
            'working'=>2,
            'completed'=>3,
            'canceled'=>4,
            'archived'=>5
        ];
        if(is_array($state)){
            $states  = [];
            foreach($state as $st){
                $states[] = $statuses[$st];
            }

            return $states;
        }
        return $statuses[$state];
    }
}