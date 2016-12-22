<?php
namespace PhotoPostType\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property string $chargeid
 * @property string $email
 * @property string $shipping_name
 * @property string $shipping_address_line_1
 * @property string $shipping_address_zip
 * @property string $shipping_address_state
 * @property string $shipping_address_city
 * @property string $shipping_address_country
 * @property bool $shipped
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Order extends Entity
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
        '*' => true,
        'id' => false
    ];
}
