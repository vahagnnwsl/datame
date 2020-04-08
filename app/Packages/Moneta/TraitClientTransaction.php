<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 5/26/18
 * Time: 2:22 PM
 */

namespace App\Packages\Moneta;


trait TraitClientTransaction
{
    public function generateClientTransaction($bill_id, $part_id) {
        return "p{$bill_id}_$part_id";
    }

    public function getTransactionBillId($clientTransaction) {
        return explode("_", substr($clientTransaction, 1))[0];
    }

    public function getTransactionPartId($clientTransaction) {
        return explode("_", substr($clientTransaction, 1))[1];
    }
}