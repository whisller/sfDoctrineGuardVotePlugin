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

        //$this->addListener(new Doctrine_Template_Listener_Vote());
    }
}