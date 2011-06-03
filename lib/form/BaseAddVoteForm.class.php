<?php
/**
 * Base class for adding new vote.
 *
 * @package    sfDoctrineGuardVotePlugin
 * @subpackage lib.form
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
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

        $requestParameters = sfContext::getInstance()->getRequest()->getParameter('votes');

        $this->setValidators(array(
            'model'            => new sfValidatorHasModel(),
            'pk'               => new sfValidatorDoctrineChoice(array('model' => $requestParameters['model'])),
            'vote'             => new sfValidatorChoice(array('choices' => array(0, 1))),
            'sf_guard_user_id' => new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser'))
        ));

        $this->widgetSchema->setNameFormat('votes[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}