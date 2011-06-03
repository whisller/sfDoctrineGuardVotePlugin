<?php
class BaseAddVoteForm extends BaseForm
{
    public function configure()
    {
        $this->setWidgets(array(
            'model'            => new sfWidgetFormInput(),
            'pk'               => new sfWidgetFormInput(),
            'vote'             => new sfWidgetFormChoice(array('choices' => array(0, 1))),
            'sf_guard_user_id' => new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'add_empty' => true))
        ));

        $this->setValidators(array(
            'model'            => new sfValidatorHasModel(),
            'pk'               => new sfValidatorHasVoteObject(),
            'vote'             => new sfValidatorChoice(array('choices' => array(0, 1))),
            'sf_guard_user_id' => new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser'))
        ));

        $this->widgetSchema->setNameFormat('vote[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}