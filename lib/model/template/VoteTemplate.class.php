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
        $this->hasColumn('vote', 'integer');
    }

    protected function getVoteTableInstance()
    {
        $invoker = $this->getInvoker();

        $instance = Doctrine_Core::getTable(get_class($invoker).'Vote');

        return $instance;
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

        // remove cache
        $driver = $this->getTable()->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE);

        $driver->delete(get_class($invoker).'Vote_'.$invoker->getId().'_'.$type);

        // update amount of votes in object
        $invoker->vote = $this->getVotesCountPositive()-$this->getVotesCountNegative();
        $invoker->save();
    }


    /**
     * Check if logged in user can add vote for object.
     *
     * This method is checking if user is authenticated and that he not add vote earlier.
     *
     * @return Boolean
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function checkCanAddVote($userId = null)
    {
        $user = sfContext::getInstance()->getUser();

        if (!$user->isAuthenticated()) {
            return false;
        }

        $invoker = $this->getInvoker();

        $q = Doctrine_Query::create()
                ->select('COUNT(v.id)')
                ->where('v.id = ?',                  $invoker->getId())
                ->addWhere('v.sf_guard_user_id = ?', $userId)
                ->from('sfMemVote v');

        $results = $q->execute(array(), Doctrine_Core::HYDRATE_NONE);

        return !(boolean)$results[0][0];
    }

    /**
     * Get amount of votes.
     *
     * @param  type $type
     * @return type Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    protected function getVotesCount($type)
    {
        $invoker = $this->getInvoker();

        $q = Doctrine_Query::create()
                ->select('COUNT(v.id)')
                ->where('v.id = ?',           $invoker->getId())
                ->addWhere('v.vote_type = ?', $type)
                ->from('sfMemVote v')
                ->useResultCache(true, 86400, get_class($invoker).'Vote_'.$invoker->getId().'_'.$type);

        $results = $q->execute(array(), Doctrine_Core::HYDRATE_NONE);

        return $results[0][0];
    }

    /**
     * Get amount of positive votes.
     *
     * @return Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function getVotesCountPositive()
    {
        return $this->getVotesCount(true);
    }

    /**
     * Get amount of negative votes.
     *
     * @return Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function getVotesCountNegative()
    {
        return $this->getVotesCount(false);
    }
}