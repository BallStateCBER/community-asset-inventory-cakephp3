<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * County Entity
 *
 * @property int $id
 * @property int $state_id
 * @property string $name
 * @property string $simplified
 * @property string $county_seat
 * @property int $fips
 * @property string $founded
 * @property int $square_miles
 * @property string $description
 *
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\Score[] $scores
 */
class County extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'state_id' => true,
        'name' => true,
        'simplified' => true,
        'county_seat' => true,
        'fips' => true,
        'founded' => true,
        'square_miles' => true,
        'description' => true,
        'state' => true,
        'scores' => true
    ];
}
