<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Category Entity
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property int $weight
 * @property string $description
 *
 * @property \App\Model\Entity\Category $parent_category
 * @property \App\Model\Entity\Category[] $child_categories
 * @property \App\Model\Entity\Score[] $scores
 * @property \App\Model\Entity\Source[] $sources
 */
class Category extends Entity
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
        'name' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'weight' => true,
        'description' => true,
        'parent_category' => true,
        'child_categories' => true,
        'scores' => true,
        'sources' => true
    ];
}
