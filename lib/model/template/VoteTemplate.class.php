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
    }


    /**
     * Check if logged in user can add vote for object.
     *
     * This method is checking if user is authenticated and that he not add vote earlier.
     *
     * @return Boolean
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function checkCanAddVote()
    {
        $user = sfContext::getInstance()->getUser();

        if (!$user->isAuthenticated()) {
            return false;
        }

        $invoker = $this->getInvoker();

        $vote = $this->getVoteTableInstance()->findOneByIdAndSfGuardUserId($invoker->getId(), $user->getGuardUser()->getId());

        // if we have not found object then user can add vote
        return !is_object($vote);
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

        $votesCount = $this->getVoteTableInstance()->findByIdAndVoteType($invoker->getId(), $type);

        if ($votesCount) {
            return count($votesCount);
        } else {
            return 0;
        }
    }

    /**
     * Get amount of positive votes.
     *
     * @return Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function getVotesCountPlus()
    {
        return $this->getVotesCount(true);
    }

    /**
     * Get amount of negative votes.
     *
     * @return Integer
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function getVotesCountMinus()
    {
        return $this->getVotesCount(false);
    }
}