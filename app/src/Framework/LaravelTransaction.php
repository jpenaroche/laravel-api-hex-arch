<?php

namespace App\Src\Framework;

use App\Exceptions\TransactionException;
use App\Src\Contracts\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: penna
 * Date: 2/20/19
 * Time: 11:32 AM
 */
class LaravelTransaction implements Transaction
{
    public function execute($closure)
    {
        $result = null;
        try {
            DB::beginTransaction();
            $result = $closure();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            throw new TransactionException($e);
        }

        return $result;
    }

}