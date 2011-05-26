<?php
/**
 * Class is adding routing for plugin
 *
 * @package    sfDoctrineGuardVoteRoutingPlugin
 * @subpackage lib.routing
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class sfGuardVoteRoutingPlugin
{
    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
    {
        $r = $event->getSubject();

        // preprend our routes


    }
}