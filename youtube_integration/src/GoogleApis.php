<?php

/**
 * @file
 * Contains GoogleApis class.
 * Batch Process class to save Video data to channel fields. 
 * @author samit.khulve
 */

namespace Drupal\youtube_integration;

class GoogleApis {

  private static $count = 0;
  private static $node;

  public static function youtubeChannelVideos($entity, $channel_details, $videos_data, &$context) {
    $videoId = $videos_data['snippet']['resourceId']['videoId'];
    $message = t('Fetching %video Video & save in %title...', ['%video' => $videoId, '%title' => $entity->get('title')]);

    if (GoogleApis::$count == 0) {
      GoogleApis::$node = \Drupal::entityTypeManager()->getStorage('node')->load($entity->id());

      GoogleApis::$node->field_title->value = $channel_details['title'];
      GoogleApis::$node->body->value = $channel_details['description'];
      GoogleApis::$count++;
    }
    GoogleApis::$node->field_videos_urls[] = $videoId;
    GoogleApis::$node->save();

    $context['message'] = $message;
    $context['results'] = GoogleApis::$count;
  }

  public static function GoogleApisFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
          count($results), 'One post processed.', '@count posts processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    drupal_set_message($message);
  }

}
