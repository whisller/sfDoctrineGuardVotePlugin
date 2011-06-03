<?php
/**
 * A generator for creating table for votes.
 *
 * This generator is creating table using this pattern
 * <code>
 * yourModelNamePostfix
 *
 * e.g. yourModelNameVote
 * </code>
 *
 * @package    sfDoctrineGuardVotePlugin
 * @subpackage lib.model.template
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class VoteRecordGenerator extends Doctrine_Record_Generator
{
    protected $_options = array(
                            'className'     => '%CLASS%Vote',
                            'tableName'     => '%TABLE%_vote',
                            'fields'        => array(),
                            'generateFiles' => false,
                            'table'         => false,
                            'pluginTable'   => false,
                            'children'      => array(),
                            'options'       => array(),
                            'cascadeDelete' => true,
                            'appLevelDelete'=> false
                            );

    /**
     * __construct
     *
     * @param string $options
     * @return void
     */
    public function __construct($options)
    {
        $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
    }

    public function buildRelation()
    {
        $this->buildForeignRelation('Votes');
        $this->buildLocalRelation();
    }

    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('notnull' => true, 'primary' => true));
        $this->hasColumn('vote_type',        'boolean');
        $this->hasColumn('sf_guard_user_id', 'integer', null, array('notnull' => true, 'primary' => true));
        $this->index('id_sf_guard_user_id', array('fields' => array('id', 'sf_guard_user_id'),
                                                  'type'   => 'unique'));
    }

    public function setUp()
    {
        parent::setUp();

        $this->hasOne('sfGuardUser as User', array(
             'local'    => 'sf_guard_user_id',
             'foreign'  => 'id',
             'onDelete' => 'CASCADE',
             'onUpdate' => 'CASCADE'));
    }
}