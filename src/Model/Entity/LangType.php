<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LangType Entity
 *
 * @property int $id
 * @property string $name
 * @property string $keywords
 * @property string|null $description
 * @property int $sort
 * @property bool|null $show
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class LangType extends Entity
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
        'keywords' => true,
        'description' => true,
        'sort' => true,
        'show' => true,

        // 日時は変更させない
        // 'created_at' => true,
        // 'updated_at' => true,
    ];
}
