<?php
/**
 * Action to add vote for object.
 *
 * @package    sfDoctrineGuardVotePlugin
 * @subpackage modules.sfGuardVote.actions
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
class AddAction extends sfAction
{
    /**
     * @param  sfWebRequest $request
     * @see    sfComponent::execute()
     * @author Daniel Ancuta <whisller@gmail.com>
     */
    public function execute($request)
    {
        $form = new FrontendAddVoteForm();

        $requestParameters = $request->getParameter($form->getName());

        $form->bind($requestParameters);

        if (!$form->isValid()) {
            // if there is an error then user is trying to hack you, or your implementation isn't working :)
            $errors = '';

            foreach($form->getErrorSchema() as $error) {
                $errors .= (string)$error.' ';
            }

            throw new sfException($errors);
        } else {
            $objectTable = call_user_func($requestParameters['model'].'Table::getInstance');
            $object = $objectTable->findOneById($requestParameters['pk']);

            $object->addVote($requestParameters['vote'], $this->getUser()->getGuardUser()->getId());
        }

        if (isset($requestParameters['redirect']) && 0 < mb_strlen($requestParameters['redirect'], 'utf-8')) {
             return $this->redirect($requestParameters['redirect']);
        } else {
            return $this->redirect($this->getUser()->getReferer($request->getReferer()));
        }
    }
}