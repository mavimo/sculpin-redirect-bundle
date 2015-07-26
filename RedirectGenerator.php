<?php

/*
 * This file is a part of Sculpin.
 *
 * (c) Dragonfly Development Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mavimo\Sculpin\Bundle\RedirectBundle;

use Sculpin\Core\Sculpin;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Source\SourceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Redirect Generator.
 *
 * @author Marco Vito Moscaritolo <marco@mavimo.org>
 * @author Beau Simensen <beau@dflydev.com>
 */
class RedirectGenerator implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Sculpin::EVENT_BEFORE_RUN => 'beforeRun',
        );
    }

    public function beforeRun(SourceSetEvent $sourceSetEvent)
    {
        $sourceSet = $sourceSetEvent->sourceSet();

        foreach ($sourceSet->updatedSources() as $source) {
            if ($source->isGenerated()) {
                // Skip generated sources.
                continue;
            }

            if (!$source->data()->get('redirect') && !$source->data()->get('full_redirect')) {
                // Skip source that do not have redirect.
                continue;
            }

            if ($source->data()->get('redirect')) {
                foreach ($source->data()->get('redirect') as $key => $redirect) {
                    // Clone current search with new sourceId.
                    $generatedSource = $source->duplicate($source->sourceId() . ':' . $redirect);

                    // Set destination is original source.
                    $generatedSource->data()->set('destination', $source);

                    // Overwrite permalink.
                    $generatedSource->data()->set('permalink', $redirect);

                    // Add redirect.
                    $generatedSource->data()->set('layout', 'redirect');

                    // Make sure Sculpin knows this source is generated.
                    $generatedSource->setIsGenerated();

                    // Add the generated source to the source set.
                    $sourceSet->mergeSource($generatedSource);
                }
            }

            if ($source->data()->get('full_redirect')) {
                $fullRedirect = $source->data()->get('full_redirect');

                if (array_key_exists('origin', $fullRedirect) && array_key_exists('destination', $fullRedirect)) {
                    $origin = $fullRedirect['origin'];
                    $destination = $fullRedirect['destination'];

                    // Set redirect destination.
                    $source->data()->set('destination', $destination);

                    // Overwrite permalink.
                    $source->data()->set('permalink', $origin);

                    // Add redirect.
                    $source->data()->set('layout', 'redirect');

                    $sourceSet->mergeSource($source);
                }
            }
        }
    }
}
