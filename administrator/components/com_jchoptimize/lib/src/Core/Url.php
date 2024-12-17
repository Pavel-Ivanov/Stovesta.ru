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

namespace JchOptimize\Core;

use Joomla\Uri\Uri;

\defined('_JCH_EXEC') or exit('Restricted access');
abstract class Url
{
    /**
     * Returns the absolute url of a relative url in a file.
     *
     * @param string $url          Url to modify
     * @param string $externalFile current file that contains the url or use uri of server if url is in an inline declaration
     */
    public static function toAbsolute(string $url, string $externalFile = ''): string
    {
        // If file path already absolute just return
        if (self::isAbsolute($url)) {
            return $url;
        }
        if ('' == $externalFile) {
            $externalFile = \JchOptimize\Core\SystemUri::currentUrl();
        }
        $oExternalURI = new Uri($externalFile);
        $oCurrentURI = new Uri($url);
        $sCurrentHost = $oCurrentURI->getHost();
        // If url is relative add to external uri path
        if (self::isPathRelative($url)) {
            $oCurrentURI->setPath(\dirname($oExternalURI->getPath()).'/'.$oCurrentURI->getPath());
        }
        // Update current url with scheme and host of external file
        $sExternalHost = $oExternalURI->getHost();
        $sExternalScheme = $oExternalURI->getScheme();
        // Only add host if current file is without host
        if (!empty($sExternalHost) && empty($sCurrentHost)) {
            $oCurrentURI->setHost($sExternalHost);
        }
        if (!empty($sExternalScheme)) {
            $oCurrentURI->setScheme($sExternalScheme);
        }
        $sAbsUrl = $oCurrentURI->toString();
        $host = $oCurrentURI->getHost();
        // If url still not absolute but contains a host then return a protocol relative url
        if (!self::isAbsolute($sAbsUrl) && !empty($host)) {
            return '//'.$sAbsUrl;
        }

        return $sAbsUrl;
    }

    /**
     * Check is url is an absolute path.
     */
    public static function isAbsolute(string $url): bool
    {
        return \preg_match('#^http#i', $url);
    }

    /**
     * Checks if url is a relative path.
     */
    public static function isPathRelative(string $url): bool
    {
        return self::isHttpScheme($url) && !self::isAbsolute($url) && !self::isProtocolRelative($url) && !self::isRootRelative($url) && \preg_match('#^[a-zA-Z0-9._~!$&\'()*+,;=:@-]#', $url);
    }

    public static function isHttpScheme(string $url): bool
    {
        return !\preg_match('#^(?!https?)[^:/]+:#i', $url);
    }

    /**
     * Check if url is protocol relative.
     */
    public static function isProtocolRelative(string $url): bool
    {
        return \preg_match('#^//#', $url);
    }

    /**
     * Checks if url is relative to the document root.
     */
    public static function isRootRelative(string $url): bool
    {
        return \preg_match('#^/[^/]#', $url);
    }

    /**
     * Checks if url is using ssl.
     */
    public static function isSSL(string $url): bool
    {
        return \preg_match('#^https#i', $url);
    }

    public static function isDataUri(string $url): bool
    {
        return \preg_match('#^data:#i', $url);
    }

    public static function isInvalid(string $url): bool
    {
        return empty($url) || '' == \trim($url, ' /\\') || \trim($url, ' /\\') == \trim(\JchOptimize\Core\SystemUri::baseFull(), ' /\\') || \trim($url, ' /\\') == \trim(\JchOptimize\Core\SystemUri::basePath(), ' /\\');
    }

    /**
     * Changes an absolute url to a protocol relative url.
     */
    public static function AbsToProtocolRelative(string $url): bool
    {
        return \preg_replace('#https?:#i', '', $url);
    }

    /**
     * Changes a url to a root relative url.
     *
     * @param string $currentFile file path that the url is found in
     */
    public static function toRootRelative(string $url, string $currentFile = ''): string
    {
        if (self::isPathRelative($url)) {
            $url = (empty($currentFile) ? '' : \dirname($currentFile).'/').$url;
        }
        $url = (new Uri($url))->toString(['path', 'query', 'fragment']);
        if (self::isPathRelative($url)) {
            $url = \rtrim(\JchOptimize\Core\SystemUri::basePath(), '\\/').'/'.$url;
        }

        return $url;
    }

    /**
     * Determines if this url will need to be accessed using an http adapter.
     */
    public static function requiresHttpProtocol(string $url): bool
    {
        return \preg_match('#\\.php|^(?![^?\\#]*\\.(?:css|js|png|jpe?g|gif|bmp|webp|svg)(?:[?\\#]|$)).++#i', $url);
    }
}
