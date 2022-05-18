<?php


namespace App\Services;


use App\Jobs\LogToSlack;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WalletTransaction
{


    public static function transfer(int $fromWalletId, $toWalletId, float $amount, string $reference, string $narration): \App\Models\WalletTransaction
    {

        $amount = abs($amount);

        return DB::transaction(static function() use ($narration, $amount, $toWalletId, $fromWalletId) {

            if ( $fromWalletId === $toWalletId) {
                throw new RuntimeException('You cannot perform this operation.');
            }

            //check if wallet to be debited exists
            $fromWallet = Wallet::lockForUpdate()->find($fromWalletId);
            if ( !$fromWallet->exists() ) {
                throw new RuntimeException('Invalid wallet account.');
            }

            //check if wallet to be credited exists
            $toWallet = Wallet::lockForUpdate()->find($toWalletId);
            if ( !$toWallet->exists() ) {
                throw new RuntimeException('Invalid wallet account');
            }

            //check for enough balance
            //also check if wallet is virtual and can be negative balance
            if ( !$fromWallet->is_virtual && !$fromWallet->can_be_negative && $amount > $fromWallet->balance ) {
                LogToSlack::dispatch('Purchase with insufficient balance', [
                    'user' => $fromWallet->user->name,
                    'amount' => $amount,
                    'host' => request()->getHost(),
                    'narration' => $narration,
                    'ip - client ip' => request()->ip() .' || '. request()->getClientIp(),
                ]);
                throw new RuntimeException('Insufficient balance.');
            }

        });
    }

}
