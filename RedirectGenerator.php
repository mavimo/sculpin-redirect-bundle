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

use Sculpin\Core\DataProvider\DataProviderManager;
use Sculpin\Core\Generator\GeneratorInterface;
use Sculpin\Core\Source\SourceInterface;

/**
 * Redirect Generator.
 *
 * @author Marco Vito Moscaritolo <marco@mavimo.org>
 */
class RedirectGenerator implements GeneratorInterface
{
    /**
     * Data Provider Manager
     *
     * @var DataProviderManager
     */
    protected $dataProviderManager;

    /**
     * Constructor
     *
     * @param DataProviderManager $dataProviderManager Data Provider Manager
     */
    public function __construct(DataProviderManager $dataProviderManager, $maxPerPage)
    {
        $this->dataProviderManager = $dataProviderManager;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(SourceInterface $source)
    {
        $sources = array();
        foreach ($source->data->get('redirect') as $key => $redirect) {
            // Clone current search with new sourceId.
            $generatedSource = $source->duplicate($redirect);

            // Get destination path.
            $source = $generatedSource->data()->get('pernalink');

            // Overwrite permalink.
            $generatedSource->data()->set('permalink', $redirect);

            // Set destination in generated source.
            $generatedSource->data()->set('destination', $source);

            $source[] = $generatedSource;
        }
        return $source;
    }
}
