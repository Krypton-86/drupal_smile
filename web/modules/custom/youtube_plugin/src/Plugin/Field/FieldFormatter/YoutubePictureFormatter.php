<?php

namespace Drupal\youtube_plugin\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the YouTube formatter.
 *
 * @FieldFormatter(
 *   id = "youtube_picture",
 *   module = "youtube_plugin",
 *   label = @Translation("Display clickbate picture for video"),
 *   field_types = {
 *     "youtube_plugin"
 *   }
 * )
 */
class YoutubePictureFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $item->value, $matches);

      if (!empty($matches)) {
        $content = '<a href="' . $item->value . '" target="_blank"><img src="http://img.youtube.com/vi/' . $matches[0] . '/0.jpg"></a>';
        $elements[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'p',
          '#value' => $content,
        ];
      }

    }

    return $elements;
  }

}
