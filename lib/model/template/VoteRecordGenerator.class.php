<?php
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
        $this->buildForeignRelation('Vote');
        $this->buildLocalRelation();
    }

    /**
     * buildDefinition
     *
     * @param object $Doctrine_Table
     * @return void
     */
    public function setTableDefinition()
    {
        $this->hasColumn('test', 'string', '255');
    }
}