<?php

use App\Enums\SupportedLocale;

dataset('locales', function () {
    return SupportedLocale::values();
});
