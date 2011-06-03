<?php
/**
 * This class is extending from BaseAddVoteForm and should be use to add vote on frontend apliaction.
 *
 * @package    sfDoctrineGuardVotePlugin
 * @subpackage lib.form
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class FrontendAddVoteForm extends BaseAddVoteForm
{
    public function configure()
    {
        parent::configure();

        $this->setWidgets(array(
            'model'            => new sfWidgetFormInput(array('type' => 'hidden')),
            'pk'               => new sfWidgetFormInput(array('type' => 'hidden')),
            'vote'             => new sfWidgetFormInput(array('type' => 'hidden')),
            'sf_guard_user_id' => new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'add_empty' => true),
                                                                 array('style' => 'display: none;'))
        ));

        $this->widgetSchema->setNameFormat('votes[%s]');
    }
}