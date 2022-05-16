<?php

use Illuminate\Support\Facades\Log;

function logSlack($message, $data) {
    Log::channel('slack')->critical($message, $data);
}
