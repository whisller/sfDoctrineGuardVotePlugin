<?php
/**
 * sfDoctrineGuardVotePlugin configuration.
 *
 * @package    sfDoctrineGuardVotePlugin
 * @subpackage config
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class sfDoctrineGuardVotePluginConfiguration extends sfPluginConfiguration
{
    /**
     * @see sfPluginConfiguration
     */
    public function initialize()
    {
        if (sfConfig::get('app_sf_doctrine_guard_vote_plugin_routes_register', true) && in_array('sfGuardVote', sfConfig::get('sf_enabled_modules', array()))) {
            $this->dispatcher->connect('routing.load_configuration', array('sfGuardVoteRoutingPlugin', 'listenToRoutingLoadConfigurationEvent'));
        }
    }
}