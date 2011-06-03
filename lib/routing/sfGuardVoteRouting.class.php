<?php
/**
 * Class is adding routing for plugin
 *
 * @package    sfDoctrineGuardVoteRoutingPlugin
 * @subpackage lib.routing
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class sfGuardVoteRouting
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

        $r->prependRoute('sf_doctrine_guard_vote_plugin_add_vote', new sfRoute('/add-vote', array('module' => 'sfGuardVote',
                                                                                                  'action' => 'Add')));
    }
}