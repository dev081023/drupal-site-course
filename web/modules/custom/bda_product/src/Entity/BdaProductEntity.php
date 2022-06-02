<?php

namespace Drupal\bda_product\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the product entity.
 *
 * @ingroup product
 *
 * @ContentEntityType(
 *   id = "product",
 *   label = @Translation("Product"),
 *   label_collection = @Translation("Product"),
 *   label_singular = @Translation("product item"),
 *   label_plural = @Translation("product items"),
 *   label_count = @PluralTranslation(
 *      singular = "@count product item",
 *      plural = "@count product items"
 * ),
 *   bundle_label = @Translation("Product type"),
 *   base_table = "product",
 *   data_table = "product_field_data",
 *   entity_keys = {
 *     "id" = "pid",
 *   },
 * )
 */
class BdaProductEntity extends ContentEntityBase implements ContentEntityInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields['pid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('PID'))
      ->setDescription(t('The PID of the Product entity.'))
      ->setReadOnly(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title product'))
      ->setDescription(t('The title of the product'))
      ->setRequired(TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDescription('The description of the product')
      ->setRequired(TRUE);

    $fields['price'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Price'))
      ->setDescription(t('The price of the product'))
      ->setRequired(TRUE);

    return $fields;
  }

}
