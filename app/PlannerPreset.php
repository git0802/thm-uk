<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Relationships\BelongsTo\Store;

class PlannerPreset extends Model
{
    use Store;

    const CASE_WEEK       = 1;
    const CASE_DAY        = 2;
    const CASE_BREAKFAST  = 3;
    const CASE_LUNCH      = 4;
    const CASE_DINNER     = 5;
    const CASE_SNACKS     = 6;


    const DAYS = [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    ];

    const MEALS = [
        'breakfast',
        'lunch',
        'dinner',
        'snacks'
    ];

    protected $fillable = [
        'store_id',
        'preset_case',
        'preset',
        'name',
        'description',
        'is_published'
    ];

    protected $casts = [
        'preset' => 'array'
    ];


    /**
     * Iterate through class constraints  and return preset cases (based on prefix)
     *
     * @return array
     */

    public static function getCases()
    {
        $prefix = "CASE_";
        $reflector = new \ReflectionClass(PlannerPreset::class);
        $constants = $reflector->getConstants();
        $values = [];

        foreach($constants as $constant => $value) {
            if(strpos($constant, $prefix) !==false) {
                $values[substr_replace($constant, '', 0, strlen($prefix))] = $value;
            }
        }
        return $values;
    }

    /**
     * Find case by its value
     *
     * @param int $value
     * @return array|null
     */

    public static function getCaseByValue(int $value)
    {
        $cases = self::getCases();
        $key = array_search($value, $cases);
        if($key) {
            return [
                'case' =>  strtolower($key),
                'case_id' => $cases[$key]
            ];
        } else {
            return null;
        }
    }

    /**
     * Build array with eatings as keys
     *
     * @return array
     */

    public function buildArray(): array
    {
        return array_merge(self::buildEmptyArray(), $this->preset);
    }

    public static function buildEmptyArray()
    {
        foreach(PlannerPreset::DAYS as $day) {
            foreach (PlannerPreset::MEALS as $eating) {
                $items[$day][$eating] = [];
            }
        }
        return $items;
    }

    public function savePreset($preset)
    {
        return $this->update(['preset' => $preset]);
    }


    /**
     * Publish preset and un-publish others that related to store
     *
     * @return bool
     */

    public function publishPreset()
    {
        $this->is_published = true;
        $this->save();
        return PlannerPreset::where('is_published', true)->where('store_id', $this->store_id)
            ->where('id', '!=' ,$this->id)
            ->update(['is_published' => false]);
    }

}
