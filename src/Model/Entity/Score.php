<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Score Entity
 *
 * @property int $id
 * @property int $county_id
 * @property bool $is_state_average
 * @property int $category_id
 * @property string $value
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\County $county
 * @property \App\Model\Entity\Category $category
 */
class Score extends Entity
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
        'is_state_average' => true,
        'category_id' => true,
        'value' => true,
        'created' => true,
        'modified' => true,
        'county' => true,
        'category' => true
    ];
}
