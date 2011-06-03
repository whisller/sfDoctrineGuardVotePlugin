<?php
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

        $form->bind($request->getParameter($form->getName()));

        if (!$form->isValid()) {
            // if there is an error then user is trying to hack you, or your implementation isn't working :)
            $errors = '';

            foreach($form->getErrorSchema() as $error) {
                $errors .= (string)$error.' ';
            }

            throw new sfException($errors);
        } else {
            $objectTable = call_user_func($request->getParameter('object').'Table::getInstance');
            $object = $objectTable->findOneById($request->getParameter('pk'));

            $object->addVote($request->getParameter('vote'));
        }

        return $this->redirect($this->getUser()->getReferer($request->getReferer()));
    }
}