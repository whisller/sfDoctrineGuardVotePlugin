<?php
/**
 * This template is adding couple of special methods for your object.
 *
 * @package    sfDoctrineVotePlugin
 * @subpackage lib.model.template
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class Doctrine_Template_Vote extends Doctrine_Template
{
    const CAND_ADD_VOTE_PREFIX = 'sfGuardVote_CanAddVote_';

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->_plugin = new VoteRecordGenerator($this->_options);
    }

    public function setUp()
    {
        $this->_plugin->initialize($this->_table);
    }

    public function setTableDefinition()
    {
        $this->hasColumn('vote',          'integer', null, array('default' => 0));
        $this->hasColumn('vote_positive', 'integer', null, array('default' => 0));
        $this->hasColumn('vote_negative', 'integer', null, array('default' => 0));
    }

    /**
     * Adding new vote for object.
     *
     * @param Integer $vote
     * @param Integer $userId
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function addVote($type, $userId)
    {
        $invoker = $this->getInvoker();

        $invokerVoteClassName = get_class($invoker).'Vote';

        $invokerVote = new $invokerVoteClassName;
        $invokerVote->id               = $invoker->getId();
        $invokerVote->vote_type        = $type;
        $invokerVote->sf_guard_user_id = $userId;
        $invokerVote->save();

        $driver = $this->getTable()->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE);

        if (!sfConfig::get('app_sfDoctrineGuardVotePlugin_authorCanAddVote', false)) {
            $driver->save(self::CAND_ADD_VOTE_PREFIX.get_class($invoker).'_'.$invoker->getId().'_'.$userId, false);
        }

        $votesPositive = $this->getVotesCountPositive(true);
        $votesNegative = $this->getVotesCountNegative(true);

        $invoker->setVotePositive($votesPositive);
        $invoker->setVoteNegative($votesNegative);
        $invoker->setVote($votesPositive - $votesNegative);

        $invoker->save();
    }


    /**
     * Check if logged in user can add vote for object.
     *
     * This method is checking if user is authenticated and that he not add vote earlier.
     *
     * @param  Integer $userId   Id of user for which we are checking his posibility to add vote
     * @param  Boolean $useCache Use or not value from cache for check if user can add or not vote
     * @return Boolean
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function checkCanAddVote($userId, $useCache = true)
    {
        $user = sfContext::getInstance()->getUser();

        if (!$user->isAuthenticated()) {
            return false;
        }

        $invoker = $this->getInvoker();

        if (!sfConfig::get('app_sfDoctrineGuardVotePlugin_authorCanAddVote', false)) {
            if ($userId == $invoker->getSfGuardUserId()) {
                return false;
            }
        }

        $driver = $this->getTable()->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE);

        $cacheId = self::CAND_ADD_VOTE_PREFIX.get_class($invoker).'_'.$invoker->getId().'_'.$userId;

        if ($driver->contains($cacheId) && $useCache) {
            $canAddVote = $driver->fetch($cacheId);
        } else {
            $q = Doctrine_Query::create()
                    ->select('COUNT(v.id)')
                    ->where('v.id = ?',                  $invoker->getId())
                    ->addWhere('v.sf_guard_user_id = ?', $userId)
                    ->from('sfMemVote v');

            $results = $q->execute(array(), Doctrine_Core::HYDRATE_NONE);

            $canAddVote = !(boolean)$results[0][0];

            $driver->save($cacheId, $canAddVote);
        }

        return $canAddVote;
    }

    /**
     * Get amount of votes.
     *
     * @param  type $type
     * @return type Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    protected function getVotesCount($type, $useCache = true)
    {
        $invoker = $this->getInvoker();

        $q = Doctrine_Query::create()
                ->select('COUNT(v.id)')
                ->where('v.id = ?',           $invoker->getId())
                ->addWhere('v.vote_type = ?', $type)
                ->from('sfMemVote v');

        $results = $q->execute(array(), Doctrine_Core::HYDRATE_NONE);

        return $results[0][0];
    }

    /**
     * Get amount of positive votes.
     *
     * @return Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function getVotesCountPositive($inRealTime = false)
    {
        if ($inRealTime) {
            $count = $this->getVotesCount(true);
        } else {
            $count = $this->getInvoker()->getVotePositive();
        }

        return (int)$count;
    }

    /**
     * Get amount of negative votes.
     *
     * @return Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function getVotesCountNegative($inRealTime = false)
    {
        if ($inRealTime) {
            $count = $this->getVotesCount(false);
        } else {
            $count = $this->getInvoker()->getVoteNegative();
        }

        return (int)$count;
    }
}