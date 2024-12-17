<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads.
 *
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2022 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 *  If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Core\Css\Callbacks;

use JchOptimize\Core\Css\Parser;
use JchOptimize\Core\Helper;

\defined('_JCH_EXEC') or exit('Restricted access');
class BackgroundImagesLazyLoad extends \JchOptimize\Core\Css\Callbacks\AbstractCallback
{
    public function processMatches($matches, $context): string
    {
        if (\preg_match('#'.Parser::cssUrlWithCaptureValueToken(\true).'#', $matches[0], $m)) {
            // Don't need to lazy-load data-image
            if (0 !== \strpos($m[1], 'data:image')) {
                // Remove the background image
                $matches[0] = \str_replace($m[0], '', $matches[0]);
                // Remove any empty background declarations
                $matches[0] = \preg_replace('#background(?:-image)?\\s*+:\\s*+;#', '', $matches[0]);
                // Add the lazy-loaded image to CSS
                return $matches[0].'.'.Helper::cssSelectorsToClass($matches[3]).'.jch-lazyloaded{background-image:'.$m[0].'}';
            }
        }

        return $matches[0];
    }
}
