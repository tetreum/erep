<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawProposal extends Model
{
    // if you increase this, edit peque.congress too (JS)
    const TYPE_NATURAL_ENEMY = 1;
    const TYPE_MUTUAL_PROTECTION_PACT = 2;
    const TYPE_WORK_TAX = 3;
    const TYPE_MANAGER_TAX = 4;
    const TYPE_IMPEACHMENT = 5;
    const TYPE_TRANSFER_FUNDS = 6;
    const TYPE_CEASE_FIRE = 7;

    public static $validLawTypes = [
        self::TYPE_NATURAL_ENEMY,
        self::TYPE_MUTUAL_PROTECTION_PACT,
        self::TYPE_WORK_TAX,
        self::TYPE_MANAGER_TAX,
        self::TYPE_IMPEACHMENT,
        self::TYPE_TRANSFER_FUNDS,
        self::TYPE_CEASE_FIRE
    ];

    protected $fillable = ["uid", "type", "country", "reason", "target_country", "amount", "yes", "no", "expected_votes", "finished"];
}
