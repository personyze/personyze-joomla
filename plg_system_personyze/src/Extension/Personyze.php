<?php

/**
 * @copyright   (C) Personyze. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Personyze\Plugin\System\Personyze\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Adds the Personyze tag to every front-end page.
 *
 * Two things here are Joomla's rules rather than Personyze's:
 *
 * - onBeforeCompileHead is used rather than onAfterRender. The head is still a
 *   structured document at that point, so the snippet is added through the
 *   document API instead of by string-replacing rendered HTML, which is what
 *   breaks when a template does something unexpected.
 * - The administrator application is skipped. Personalizing the Joomla back end
 *   is never wanted, and it would report the administrator's own clicks as site
 *   traffic.
 */
final class Personyze extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns the events this subscriber listens to.
     *
     * @return  array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return ['onBeforeCompileHead' => 'injectTag'];
    }

    /**
     * Adds the tracking snippet to the document head.
     *
     * @return  void
     */
    public function injectTag(): void
    {
        $app = $this->getApplication();

        if (!$app->isClient('site')) {
            return;
        }

        $accountId = (int) $this->params->get('account_id', 0);

        // No account ID means no tag at all, rather than a tag reporting to
        // account 0.
        if ($accountId <= 0) {
            return;
        }

        // JSON_HEX_TAG is what stops a domain containing "</script>" from
        // ending the block early; the value is operator-supplied free text.
        $domains = json_encode(
            (string) $this->params->get('tracking_domains', ''),
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        $js = <<<JS
window._S_T ||
(function(d){
  var s = d.createElement('script'),
    u = s.onload===undefined && s.onreadystatechange===undefined,
    i = 0,
    f = function() {window._S_T ? (_S_T.async=true) && _S_T.setup($accountId, $domains) : i++<120 && setTimeout(f, 600)},
    h = d.getElementsByTagName('head');
  s.async = true;
  s.src = '//counter.personyze.com/stat-track-lib.js';
  s.onload = s.onreadystatechange = f;
  (h && h[0] || d.documentElement).appendChild(s);
  if (u) f();
})(document);
JS;

        $app->getDocument()->addScriptDeclaration($js);
    }
}
