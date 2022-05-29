<?php


namespace Drupal\bda_clean_text\Plugin\Field\FieldFormatter;


use Drupal\Core\Annotation\Translation;
use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * @FieldFormatter(
 *   id="test_list",
 *   label=@Translation("Test List"),
 *   field_types={
 *      "list_string",
 *   }
 * )
 *
 * @package Drupal\bda_clean_text\Plugin\Field\FieldFormatter
 */
class TestListFormatter extends FormatterBase {

  /**
   * @inheritDoc
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $output = [];
    foreach ($items as $item) {
      $output = ['#markup' => $item->value];
    }
    return $output;
  }

}
