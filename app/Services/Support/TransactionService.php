<?php
namespace App\Services\Support;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionService
{
    /**
     * Run the given callback inside a DB transaction.
     *
     * @param Closure $callback
     * @return mixed
     * @throws Throwable
     */
    public function run(Closure $callback): mixed
    {
        DB::beginTransaction();

        try {
            $result = $callback();

            DB::commit();
            return $result;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
