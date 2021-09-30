<?php

namespace Drupal\youtube_plugin\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the YouTube formatter.
 *
 * @FieldFormatter(
 *   id = "youtube_player",
 *   module = "youtube_plugin",
 *   label = @Translation("Display YouTube video player"),
 *   field_types = {
 *     "youtube_plugin"
 *   }
 * )
 */
class YoutubePlayerFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $item->value, $matches);

      if (!empty($matches)) {
        $elements[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'iframe',
          '#attributes' => [
            'id' => 'ytplayer',
            'type' => 'text/html',
            'width' => '800',
            'height' => '430',
            'frameborder' => '0',
            'allowfullscreen' => '1',
            'src' => "https://www.youtube.com/embed/$matches[0]",
          ],
        ];
      }

    }

    return $elements;
  }

}
