<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RelativeHomeValue Entity
 *
 * @property int $id
 * @property int $county_id
 * @property bool $is_neighboring
 * @property bool $is_ratio
 * @property bool $is_growth
 * @property int $year
 * @property float $value
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\County $county
 */
class RelativeHomeValue extends Entity
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
        'county_id' => true,
        'is_neighboring' => true,
        'is_ratio' => true,
        'is_growth' => true,
        'year' => true,
        'value' => true,
        'created' => true,
        'county' => true
    ];
}
