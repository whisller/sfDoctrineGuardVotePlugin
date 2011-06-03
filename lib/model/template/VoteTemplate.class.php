<?php
/**
 * This behavior is adding two new columns to table with behavior, vote_plus and vote_minus that represents user votes.
 *
 * @package    sfDoctrineVotePlugin
 * @subpackage lib
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

    /**
     * @see    Doctrine_Template::setTableDefinition()
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function setTableDefinition()
    {
        $this->hasColumn('votes_plus',  'integer', null, array('default' => 0));
        $this->hasColumn('votes_minus', 'integer', null, array('default' => 0));
    }

    /**
     * Method is adding new vote for object.
     *
     * @param Integer $vote
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function addVote($vote)
    {
        var_dump($vote);die();
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


    }
}