<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Access Entity
 *
 * @property int $id
 * @property int $yyyy
 * @property int $mm
 * @property int $dd
 * @property int $pv
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Access extends Entity
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
        'yyyy' => true,
        'mm' => true,
        'dd' => true,
        'pv' => true,
       
        // 日時は変更させない
        // 'created_at' => true,
        // 'updated_at' => true,
    ];
}
